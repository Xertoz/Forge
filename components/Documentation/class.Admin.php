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
	
	use forge\components\SiteMap;
	use \forge\components\Templates;
	use forge\HttpException;

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
		 * Expose component documentation
		 */
		static public function component() {
			$component = \forge\Get::getString('component');
			return Templates::display('components/Documentation/tpl/adm.component.php', ['ref' => new \ReflectionClass('forge\\components\\'.$component)]);
		}

		/**
		 * List all components
		 */
		static public function components() {
			return Templates::display('components/Documentation/tpl/adm.components.php', ['components' => \forge\Addon::getComponents()]);
		}
		
		/**
		* Show the connection list
		*/
		static public function index() {
			return Templates::display('components/Documentation/tpl/adm.index.php');
		}

		/**
		 * Look up a class name and view its documentation
		 */
		static public function lookup() {
			$class = \forge\Get::getString('class');
			$ns = explode('\\', $class);

			if (count($ns) === 2 && $ns[0] === 'forge')
				$url = 'api?class='.urlencode($ns[1]);
			elseif (count($ns) > 2 && $ns[0] === 'forge' && $ns[1] === 'components')
				$url = 'component?component='.$ns[2].(count($ns) > 3? '&class='.implode('\\', array_slice($ns, 3)) : '');
			elseif (count($ns) > 2 && $ns[0] === 'forge' && $ns[1] === 'modules')
				$url = 'module?module='.$ns[2].(count($ns) > 3? '&class='.implode('\\', array_slice($ns, 3)) : '');
			else
				throw new HttpException('Class not found', HttpException::HTTP_NOT_FOUND);

			SiteMap::redirect($url);
		}

		/**
		 * Expose module documentation
		 */
		static public function module() {
			$module = \forge\Get::getString('module');
			return Templates::display('components/Documentation/tpl/adm.module.php', ['ref' => new \ReflectionClass('forge\\modules\\'.$module)]);
		}

		/**
		 * List all modules
		 */
		static public function modules() {
			return Templates::display('components/Documentation/tpl/adm.modules.php', ['modules' => \forge\Addon::getModules()]);
		}
	}