<?php
	/**
	* class.Admin.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Documentation;
	
	use \forge\components\Templates;

	/**
	* Software component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		/**
		 * Expose API documentation
		 */
		static public function api() {
			// Did the user want to view a class?
			$class = \forge\Get::getString('class');
			
			// Template vars
			$vars = [];
			
			if ($class === null) {
				// Scan API folder
				$classes = glob(FORGE_PATH.'/api/class.*.php');
				
				// Clean up the class names
				foreach ($classes as &$class) {
					preg_match('#\/class.(\w+).php#', $class, $match);
					$class = $match[1];
				}
				
				$vars['classes'] = $classes;
			}
			else {
				$vars['class'] = $class;
				$vars['ref'] = new \ReflectionClass('forge\\'.$class);
			}
			
			return Templates::display('components/Documentation/tpl/adm.api.php', $vars);
		}
		
		/**
		* Show the connection list
		*/
		static public function index() {
			return \forge\components\Templates::display('components/Documentation/tpl/adm.index.php');
		}
	}