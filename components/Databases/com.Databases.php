<?php
	/**
	* com.Databases.php
	* Copyright 2009-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;
	use \PDO;

	/**
	* Database component
	*/
	class Databases extends \forge\Component implements \forge\components\Dashboard\InfoBox {
		use \forge\Configurable;
		
		/**
		* Extended column
		* @var array
		*/
		static private $columns = array();

		/**
		* Configuration
		* @var array
		*/
		static protected $config = array();

		/**
		* All engines currently instantiated
		* @var array
		*/
		static private $engines = array();

		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		* Create a new database connection and save it for future use
		*
		* This procedure will attempt to connect to the server temporarily and close the connection upon finishing.
		*
		* @param string Driver
		* @param string Hostname
		* @param string Database
		* @param string Table prefix
		* @param string Username
		* @param string Password
		* @return string Connection ID
		* @throws Exception
		*/
		static public function addConnection($driver,$hostname,$database,$prefix,$username,$password) {
			// Test the connection out
			try {
				$class = 'forge\components\Databases\drivers\\'.$driver;
				if (!class_exists($class) || !in_array('forge\components\Databases\Engine',class_parents($class)))
					throw new \Exception('The requested database driver is non-existant');

				$params = new Databases\Params(['engine'=>false]);
				$params->hostname = $hostname;
				$params->database = $database;
				$params->prefix = $prefix;
				$params->username = $username;
				$params->password = $password;

				$engine = new $class($params);
			}
			// If it fails, say so.
			catch (\Exception $e) {
				throw new \Exception('The driver failed with the given parameters', 0, $e);
			}

			// Create a new configuration post for this connection
			$config = array(
				'driver' => $driver,
				'hostname' => $hostname,
				'database' => $database,
				'prefix' => $prefix,
				'username' => $username,
				'password' => $password
			);

			// Write it to the configuration
			self::setConfig('connections', array_merge(
					self::getConfig('connections', array()),
					array(
						$connId = uniqid() => $config
					)
				)
			);
			self::writeConfig();

			// Return the new connection ID
			return $connId;
		}

		/**
		* Declare a new column of a model
		* Use this method to extend existing class models when required
		* @param string Model
		* @param string Column
		* @param mixed Parameters
		* @return void
		*/
		static public function declareColumn($model, $column, $parameters) {
			if (!in_array('forge\components\Databases\Table', class_parents($model)))
				throw new \Exception('Model '.$model.' was not a table');

			self::$columns[$model][$column] = $parameters;
		}

		/**
		* Delete a database connection
		*
		* This will destroy a connection's file, not close the connection. Should a connection be up to this
		* database it will be maintained until closed elsewhere in the current request, but be unreachable
		* in the next.
		*
		* @param string Connection ID
		* @return void
		* @throws Exception
		*/
		static public function deleteConnection($connId) {
			self::SecureConnectionId($connId);

			$config = self::getConfig('connections');
			unset($config[$connId]);
			self::setConfig('connections', $config, true);

			if (isset(self::$engines[$connId]))
				unset(self::$engines[$connId]);
		}

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Databases.Admin'))
				return null;

			return \forge\components\Templates::display(
				'components/Databases/tpl/inc.infobox.php',
				array(
					'databases' => count(\forge\components\Databases::getConfig('connections'))
				)
			);
		}

		/**
		* Initialize the component
		* @return void
		*/
		static public function init() {
			parent::init();

			// Start the default engine
			if (self::config('defaultConnection'))
				self::startEngine(self::config('defaultConnection'));
		}

		/**
		* Select what connection to use as default from the available ones
		* @param string Connection ID
		* @return void
		* @throws Exception
		*/
		static public function setDefaultConnection($connId) {
			// Make it secure.
			self::SecureConnectionId($connId);

			// Does it exist?
			if (!in_array($connId,array_keys(self::getConfig('connections',array()))))
				throw new \Exception('Database connection does not exist');

			// Set the new one
			self::setConfig('default', $connId);
			self::writeConfig();
		}

		/**
		* Check wether or not the given string is an eligible connection id
		* @param string Connection id
		* @return void
		* @throws Exception
		*/
		static private function secureConnectionId($connId) {
			if (!is_string($connId) || empty($connId))
				throw new \Exception('$ConnId needs to be set string');

			if (preg_match('/[^a-zA-Z0-9]/',$connId))
				throw new \Exception('Invalid character in connection id');
		}

		/**
		* Get the default connection id
		* @return string Connection ID
		* @throws Exception
		*/
		static public function getDefaultConnection() {
			return self::getConfig('default');
		}

		/**
		* Get a list of all available drivers
		* @return array
		*/
		static public function getDrivers() {
			$subspace = self::getNamespace('drivers');
			$drivers = array();

			foreach ($subspace as $subject)
				if (in_array('forge\components\Databases\Engine',class_parents($subject))) {
					$parts = explode('\\',$subject);
					$drivers[] = array_pop($parts);
				}

			return $drivers;
		}

		/**
		* This will update the database to its specified definition
		* @param string Component/Module name
		* @param string COM/MOD
		* @return void
		* @throws Exception
		*/
		static public function fixDatabase($name,$type) {
			// Get an instance from which to find tables
			$class = 'forge\\'.($type=='COM'?'components':'modules').'\\'.$name;

			// Loop over table definitions
			foreach ($class::getTables() as $model)
				if ($model::isHandled())
					(new $model)->fixIntegrity();
		}

		/**
		* Get a list of all declared columns to a model
		* @param string Model
		* @return array
		*/
		static public function getColumns($model) {
			if (!in_array('forge\components\Databases\Table', class_parents($model)))
				throw new \Exception('Model '.$model.' was not a table');

			return isset(self::$columns[$model]) ? self::$columns[$model] : array();
		}

		/**
		* Get a list of set up databases
		* @return array
		*/
		static public function getDatabaseList() {
			return self::getConfig('connections');
		}

		/**
		* Start an SQL engine
		* @param string Connection ID
		* @return \forge\components\Databases\Engine
		* @throws Exception
		*/
		static public function startEngine($id) {
			// Get the configuration
			$config = self::getConfig('connections');
			if (!isset($config[$id]))
				throw new \Exception('A database connection was not found');

			// Get the driver class
			$class = 'forge\components\Databases\drivers\\'.$config[$id]['driver'];
			if (!class_exists($class) || !in_array('forge\components\Databases\Engine',class_parents($class)))
				throw new \Exception('The requested database driver is non-existant');

			// Set the parameters
			$params = new Databases\Params(['engine'=>false]);
			$params->hostname = $config[$id]['hostname'];
			$params->database = $config[$id]['database'];
			$params->username = $config[$id]['username'];
			$params->password = $config[$id]['password'];
			$params->prefix = $config[$id]['prefix'];

			// Return the new engine instance
			return self::$engines[$id] = new $class($params);
		}

		/**
		* Get the an SQL engine
		* @param string Request a specific engine by ID?
		* @return \forge\components\Databases\Engine
		*/
		static public function getEngine($id=false) {
			if ($id === false)
				$id = self::getDefaultConnection();

			if (isset(self::$engines[$id]))
				return self::$engines[$id];
			else
				return self::startEngine($id);
		}

		/**
		* Get the current connection handle
		* @return PDO
		* @deprecated
		*/
		static public function DB() {
			return self::getEngine()->getPDO();
		}
	}