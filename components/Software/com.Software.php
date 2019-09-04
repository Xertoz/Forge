<?php
	/**
	* com.Software.php
	* Copyright 2009-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;
	use forge\components\Admin\MenuItem;
	use \forge\components\Databases;

	/**
	* Patch component
	*/
	class Software extends \forge\Component implements \forge\components\Admin\Menu {
		/**
		* Default table engine
		*/
		const DefaultTableEngine = 'MyISAM';

		/**
		* Default table comment
		*/
		const DefaultTableComment = 'Automatically maintained by Forge 4';

		/**
		* Default column comment
		*/
		const DefaultColumnComment = 'Automatically maintained by Forge 4';

		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		* Default configuration
		* @var array
		*/
		static protected $config = array(
			'modules' => array()
		);

		/**
		* Initiate this component and load selected modules
		* @return void
		*/
		static public function init() {
			parent::init();

			foreach (self::config('modules') as $module)
				\forge\Addon::loadModule($module);
		}

		/**
		 * Install a module
		 * @param string Archive (ZIP) path & name
		 * @return void
		 * @throws \Exception
		 */
		static public function installModule($file) {
			// Create the ZIP reader
			$zip = new \ZipArchive();

			// If it's not a ZIP archive, don't install
			if ($zip->open($file) !== true)
				throw new \Exception('File was not ZIP archive');

			// Proper comment?
			if ($zip->getArchiveComment() === false)
				throw new \Exception('ZIP archive was not Forge module');

			// Extract the archive to a temp folder
			$zip->extractTo('modules/'.$zip->getArchiveComment());

			// Current modules list
			$modules = @file_get_contents('config/sys.modules.php');
			if ($modules === false) {
				file_put_contents('config/sys.modules.php','<?php\n\forge\mod_load(\''.$zip->getArchiveComment().'\');\n?>');
			}
			else {
				$modules = explode("\n",$modules);
				$modules[count($modules)-1] = '\forge\mod_load(\''.$zip->getArchiveComment().'\');';
				$modules[] = '?>';
				file_put_contents('config/sys.modules.php',implode("\n",$modules));
			}

			// Install database
			self::fixDatabase($zip->getArchiveComment(),'MOD');
		}

		/**
		* Get information regarding the status of an addon
		* @param \forge\Configurable $controller Addon's controller
		* @return array
		*/
		static private function getAddonStatus($controller) {
			// Verify the database integrity
			$database = true;
			
			// Instantiate models so all dynamic classes are generated
			$models = $controller::getNamespace('db');
			foreach ($models as $model)
				new $model;
			
			// Loop all models and check them
			$models = $controller::getNamespace('db');
			foreach ($models as $model)
				if ($model::isHandled())
					$database &= (new $model)->checkIntegrity();

			return array(
				'name' => $controller::getName(),
				'version' => $controller::getVersion(),
				'config' => in_array('forge\\Configurable', class_uses($controller)) ? $controller::isConfigured() : -1,
				'database' => count($models) ? $database : -1
			);
		}

		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Software.Admin'))
				return null;
			
			$menu = new MenuItem('developer', self::l('Developer'));
			
			$menu->appendChild(new MenuItem(
				'software',
				self::l('Modules'),
				'Software'
			));
			
			return $menu;
		}

		/**
		* Get information regarding the status of a component
		* @param string Component system name
		* @return array
		*/
		static public function getComponentStatus($component) {
			return self::getAddonStatus('\\forge\\components\\'.$component);
		}

		/**
		* Get information regarding the status of a component
		* @param string Component system name
		* @return array
		*/
		static public function getModuleStatus($component) {
			return self::getAddonStatus('\\forge\\modules\\'.$component);
		}
	}