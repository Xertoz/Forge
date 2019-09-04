<?php
	/**
	* trait.Pluginnable.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* Add plugin support for addon objects
	*/
	trait Pluginnable {
		/**
		* Get a specific plugin
		* @param string $name Plugin name
		* @param string|false $type Plugin type
		* @return callback Plugin
		* @throws \Exception
		*/
		static public function getPlugin($name,$type=false) {
			// Compute the plugin class name
			$class = get_called_class().'\plugins'.($type !== false ? '\\'.$type : null).'\\'.$name;

			// If the class is not defined, we should throw an exception
			if (!class_exists($class))
				throw new \Exception('Plugin '.$name.' '.($type !== false ? '(of '.$type.' type) ' : null).'does not exist.');

			// Return the computed class
			return $class;
		}

		/**
		* Get all available plugins for this addon
		* @param string|false $type Any specific plugin type to return?
		* @return array
		*/
		static public function getPlugins($type=false) {
			// All plugins must utilize the plugins subspace of the addon
			$ns = 'plugins';

			// If we got a specific plugin type, it's another subspace name
			if ($type !== false)
				$ns .= '\\'.(string)$type;

			// Get all classes in the computed subspace
			$candidates = static::getNamespace($ns);

			// Prepare the return value
			$plugins = array();

			// Loop over all candidate classes found for plugins
			foreach ($candidates as $candidate) {
				$plugin = explode('\\',$candidate);
				$plugin = array_pop($plugin);

				// If the candidate is a plugin, append it to the return value
				if (static::isPlugin($plugin,$type))
					$plugins[] = $plugin;
			}

			// Return what we found
			return $plugins;
		}

		/**
		* Check if a declared plugin is of the right descent
		* @param string $name Plugin name
		* @param string|false $type Plugin type
		* @return bool
		*/
		static public function isPlugin($name,$type=false) {
			// Pluginnable::getPlugin could throw exceptions which would be a false return valuein this context
			try {
				// We just check for the forge\Plugin parent class here
				return is_subclass_of(static::getPlugin($name,$type),'forge\Plugin');
			}
			catch (\Exception $e) {
				return false;
			}
		}
	}