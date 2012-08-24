<?php
	/**
	* class.Addon.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* Modules and components must be derived from this class
	*/
	abstract class Addon {
		use Versioning;

		/**
		* Define permissions issued
		* @var array
		*/
		static protected $permissions = array();

		/**
		* Is this a component?
		* @return bool
		*/
		static final public function isComponent() {
			return in_array('forge\Component',class_parents(self::getName(true)));
		}

		/**
		* Is this a module?
		* @return bool
		*/
		static final public function isModule() {
			return in_array('forge\Module',class_parents(self::getName(true)));
		}

		/**
		 * Is the given component loaded?
		 * @param $component string Component's system name
		 * @return boolean
		 */
		static final public function existsComponent($component) {
			return class_exists('forge\\components\\'.$component);
		}

		/**
		 * Is the given module loaded?
		 * @param $module string Module's system name
		 * @return boolean
		 */
		static final public function existsModule($module) {
			return class_exists('forge\\modules\\'.$module);
		}

		/**
		 * Get all loaded components
		 * @param $long boolean Return the long (namespaced) class name?
		 * @return array
		 */
		static final public function getComponents($long=false) {
			$components = array();
			
			foreach (glob('components/*') as $raw) {
				$name = substr($raw, strlen('components/'));
				$components[] = $long ? 'forge\\components\\'.$name : $name;
			}
			
			return $components;
		}

		/**
		 * Get all loaded addons
		 * @param $long boolean Return the long (namespaced) class names?
		 * @return array
		 */
		static final public function getAddons($long=false) {
			$components = self::getComponents($long);
			$modules = self::getModules($long);

			$addons = array_merge($components, $modules);
			sort($addons);

			return $addons;
		}

		/**
		 * Get all loaded modules
		 * @param $long boolean Return the long (namespaced) class name?
		 * @return array
		 */
		static final public function getModules($long=false) {
			$modules = array();
			
			foreach (glob('modules/*') as $raw) {
				$name = substr($raw, strlen('modules/'));
				$modules[] = $long ? 'forge\\modules\\'.$name : $name;
			}
			
			return $modules;
		}

		/**
		* Get name
		* @param Get full class name
		* @return string
		*/
		static final public function getName($long=false) {
			$ref = explode('\\',get_called_class());
			return $long ? get_called_class() : array_pop($ref);
		}

		/**
		* Get list of tables
		* @param $long bool Full name of the classes?
		* @return array
		* @throws Exception
		*/
		static public function getTables($long=true) {
			$tables = array();
			$files = glob(($path = (substr(str_replace('\\', '/', get_called_class()), strlen('forge/')).'/db/')).'*');
			
			foreach ($files as $file) {
				preg_match('$'.$path.'class.(\w+).php$', $file, $m);
				
				if (count($m))
					$tables[] = ($long ? 'forge\\'.str_replace('/', '\\', $path) : null).$m[1];
			}

			return $tables;
		}

		/**
		* Get list of classes available in namespace
		* @param string Subspace (NULL gives all)
		* @return array
		*/
		static public function getNamespace($subspace=null) {
			$classes = array();
			$path = str_replace('\\', '/', substr(get_called_class(), strlen('forge\\'))).'/'.str_replace('\\', '/', $subspace).'/';
			
			if (($files = glob($path.'class.*.php')) !== false)
				foreach ($files as $file) {
					preg_match_all('#'.$path.'class.(\w+).php#', $file, $matches);
					$classes[] = get_called_class().'\\'.(strlen($subspace) ? $subspace.'\\' : null).array_pop($matches[1]);
				}
			
			return $classes;
		}

		/**
		* Get permissions issued
		* @return array
		*/
		static public function getPermissions() {
			$class = get_called_class();
			return $class::$permissions;
		}
	}