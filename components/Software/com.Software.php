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
	use \forge\components\Databases;

	/**
	* Patch component
	*/
	class Software extends \forge\Component implements \forge\components\Dashboard\InfoBox {
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
		* @return bool
		* @throws Exception
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
		* @param string Addon's controller
		* @return array
		*/
		static private function getAddonStatus($controller) {
			// Verify the database integrity
			$database = true;
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
		
		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Software.Admin'))
				return null;

			return \forge\components\Templates::display(
				'components/Software/tpl/inc.infobox.php',
				array(
					'addons' => count(\forge\Addon::getAddons())
				)
			);
		}
	}