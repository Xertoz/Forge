<?php
	/**
	* engine.MySQL.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases\drivers;

	/**
	* The root class for all SQL engines used
	*/
	class MySQL extends \forge\components\Databases\Engine {
		/**
		* Initiate the class and connect to the requested database
		* @param SqlParams Parameters as loaded from configuration
		* @return void
		* @throws HttpException
		*/
		public function __construct(\forge\components\Databases\Params $params) {
			try {
				$this->pdo = new \PDO(
					'mysql:dbname='.$params->database.';host='.$params->hostname,
					$params->username,
					$params->password,
					array(
						\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					)
				);
				$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				$this->database = $params->database;
				$this->prefix = $params->prefix;
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('Failed to establish a database connection',\forge\HttpException::HTTP_SERVICE_UNAVAILABLE);
			}
		}

		/**
		* Build a SELECT COUNT(*) statement for a table
		* @param \forge\components\Databases\Params $params
		* @return string
		*/
		public function buildCount(\forge\components\Databases\Params $params) {
			return $this->pdo->prepare(
				'SELECT COUNT(*)
				FROM '.$this->prefix.$params->table.'
				'.self::buildWhere($params)
			);
		}

		/**
		* Build a CREATE statement for a table
		* @param Table Table
		* @return string
		*/
		public function buildCreate(\forge\components\Databases\Table $table) {
			$create = 'CREATE TABLE `'.$this->prefix.$table->getTable().'` (';
			$lines = array();
			$indexes = ['primary' => [], 'key' => [], 'unique' => []];
			foreach ($table->getColumns(true) as $column => $object) {
				$lines[] = $object->buildCreate($column);
				foreach ($object->buildIndexes($column) as $key => $array)
					foreach ($array as $index)
						$indexes[$key][] = $index;
			}
			sort($indexes['primary']);
			sort($indexes['unique']);
			sort($indexes['key']);
			$indexes = array_merge($indexes['primary'], $indexes['unique'], $indexes['key']);
			$create .= "\n  ".implode(",\n  ",array_merge($lines,$indexes))."\n) ENGINE=InnoDB DEFAULT CHARSET=utf8";

			return $create;
		}

		/**
		* Prepare a query for deletion
		* @param SqlParams Parameters
		* @return PDOStatement
		*/
		public function buildDelete(\forge\components\Databases\Params $params) {
			$where = array();
			foreach ($params->where as $column)
				$where[] = $column.' = ?';
			$where = implode(', ',$where);

			return $this->pdo->prepare(
				'DELETE FROM '.$this->prefix.$params->table.'
				WHERE '.$where
			);
		}

		/**
		* Prepare a query for insertion
		* @param SqlParams Parameters
		* @return PDOStatement
		*/
		public function buildInsert(\forge\components\Databases\Params $params) {
			$columns = array();
			$values = array();
			foreach ($params->columns as $column) {
				$columns[] = $column;
				$values[] = '?';
			}
			$columns = implode('`, `',$columns);
			$values = implode(', ',$values);

			return $this->pdo->prepare(
				'INSERT INTO '.$this->prefix.$params->table.'
				(`'.$columns.'`)
				VALUES
				('.$values.')'
			);
		}

		/**
		* Prepare a query for selection
		* @param SqlParams Parameters
		* @return PDOStatement
		*/
		public function buildSelect(\forge\components\Databases\Params $params) {
			return $this->pdo->prepare(
				'SELECT *
				FROM '.$this->prefix.$params->table.'
				'.self::buildWhere($params).'
				'.self::buildOrder($params).'
				'.($params->limit !== false ? 'LIMIT '.intval(($params->page-1)*$params->limit).','.(int)$params->limit : null)
			);
		}

		/**
		* Prepare a query for updating
		* @param SqlParams Parameters
		* @return PDOStatement
		*/
		public function buildUpdate(\forge\components\Databases\Params $params) {
			$set = array();
			foreach ($params->columns as $column)
				$set[] = '`'.$column.'` = ?';
			$set = implode(', ',$set);

			$where = array();
			foreach ($params->where as $column)
				$where[] = '`'.$column.'` = ?';
			$where = implode(' AND ',$where);

			return $this->pdo->prepare(
				'UPDATE '.$this->prefix.$params->table.'
				SET '.$set.'
				WHERE '.$where
			);
		}

		/**
		* Verify a table's integrity
		* @param \forge\components\Databases\Table Table model
		* @return bool
		*/
		public function checkIntegrity(\forge\components\Databases\Table $table) {
			// Do we check this, at all?
			if (!$table->handleIntegrity())
				return true;

			try {
				// Get the creation statement
				$live = $this->getCreate($table);

				// Create a control create statement
				$control = $this->buildCreate($table);

				if ($live != $control)
					return false;
			}
			catch (\Exception $e) {
				return false;
			}

			return true;
		}

		/**
		* Fix the integrity
		* @param \forge\components\Databases\Table Table model
		* @return void
		*/
		public function fixIntegrity(\forge\components\Databases\Table $table) {
			if ($this->checkIntegrity($table))
				return;

			// Get the CREATE statement etc
			$create = $this->buildCreate($table);
			$name = $this->prefix.$table->getTable();

			// Does the table already exist?
			try {
				$tmp = $this->pdo->query('SELECT 1 FROM `'.$name.'` LIMIT 1')->rowCount();
			}
			catch (\Exception $e) {
				$tmp = false;
			}

			// Rename any existing table
			if ($tmp !== false)
				$this->pdo->query('RENAME TABLE `'.$name.'` TO `'.$name.'_tmp`');

			// Create the correct table
			$this->pdo->query($create);

			// Migrate data if we had any
			if ($tmp) {
				$columns = array_intersect(
					$table->getColumns(),
					$this->pdo->query('SHOW COLUMNS IN `'.$name.'_tmp`')->fetchAll(\PDO::FETCH_COLUMN)
				);

				$this->pdo->query('INSERT INTO `'.$name.'` ('.implode('`, `', $columns).') SELECT `'.implode('`, `', $columns).'` FROM `'.$name.'_tmp`');
				$this->pdo->query('DROP TABLE `'.$name.'_tmp`');
			}
		}

		/**
		* Get the current CREATE statement
		* @param Table Table
		* @return string
		*/
		public function getCreate(\forge\components\Databases\Table $table) {
			try {
				$create = $this->pdo->query(
					'SHOW CREATE TABLE `'.$this->prefix.$table->getTable().'`'
				)->fetch(\PDO::FETCH_NUM)[1];

				return preg_replace('/ AUTO_INCREMENT=(\d)+/', '', $create);
			}
			catch (\Exception $e) {
				return null;
			}
		}

		/**
		* Get a class name for a given type
		* @param SqlParams Parameters
		* @return string
		* @throws Exception
		*/
		public function getType(\forge\components\Databases\Params $params) {
			$class = $this->getNamespace().'\\'.$params->type;

			if (!class_exists($class))
				throw new \Exception('Type '.$params->type.' was not found');

			return $class;
		}
	}
