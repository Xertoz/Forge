<?php
	/**
	 * class.Configurable.php
	 * Copyright 2012-2019 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */
	
	namespace forge;
	
	/**
	 * Make a class configurable
	 */
	trait Configurable {
		/**
		 * Array to hold the key-value pairs of the configuration
		 * @var array
		 */
		static protected $__config = array();
		
		/**
		 * Get a value for the given configuration key
		 * @param $key string Key name
		 * @param $default mixed Return value for unset keys
		 * @return mixed Key value
		 */
		static protected function getConfig($key, $default=null) {
			if (!isset(static::$__config[get_called_class()]))
				self::loadConfig();
			
			return isset(static::$__config[get_called_class()][$key]) ? static::$__config[get_called_class()][$key] : $default;
		}

		/**
		 * Get all set config keys
		 * @return array
		 */
		static protected function getConfigKeys() {
			if (!isset(static::$__config[get_called_class()]))
				self::loadConfig();

			return isset(static::$__config[get_called_class()]) ? array_keys(static::$__config[get_called_class()]) : [];
		}

		/**
		 * Get the path to the configuration file
		 * @return string
		 */
		static private function getConfigPath() {
			$path = [];
			foreach (explode('\\',get_called_class()) as $piece)
				$path[] = $piece;
			
			return implode('/', $path).'.php';
		}
		
		/**
		 * Check to see wether or not a configuration exists
		 * @return bool
		 */
		static public function isConfigured() {
			return file_exists('config/'.self::getConfigPath());
		}
		
		/**
		 * Load the configuration from a file
		 * @return void
		 */
		static private function loadConfig() {
			// Produce a path depending on the namespace
			$path = FORGE_PATH.'/config/'.self::getConfigPath();
			
			if (file_exists($path))
				require_once $path;
		}

		/**
		 * Set the configuration to a specific array
		 * @param array $array key-value array
		 * @return void
		 */
		static private function makeConfig($array) {
			static::$__config[get_called_class()] = $array;
		}

		/**
		 * Set a value for the given configuration key
		 * @param $key string Key name
		 * @param $value mixed Key value
		 * @param $flush bool Write to disk?
		 * @return void
		 * @throws \Exception
		 */
		static protected function setConfig($key, $value, $flush=false) {
			if (!isset(static::$__config[get_called_class()]))
				self::loadConfig();
			
			static::$__config[get_called_class()][$key] = $value;
			
			if ($flush)
				self::writeConfig();
		}

		/**
		 * Write the configuration to disk
		 * @throws \Exception
		 */
		static protected function writeConfig() {
			// Produce a path depending on the namespace
			$path = self::getConfigPath();
			
			// Create a configuration code
			$config = '<?php static::makeConfig('.var_export(static::$__config[get_called_class()], true).');';
			
			// Finally, put it in place
			components\Files\ConfigFile::create($path)->set($config, true);

			// Clear any APC cache if available
			if (function_exists('apc_clear_cache'))
				apc_clear_cache();
		}
	}