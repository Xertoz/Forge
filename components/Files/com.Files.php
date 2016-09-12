<?php
	/**
	* com.Files.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* File manager
	*/
	class Files extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Dashboard\InfoBox {
		use \forge\Configurable;
		
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];
		
		/**
		 * Create the required repositories upon installation
		 */
		static public function createRepositories() {
			$cache = self::getConfig('cache', false);
			$files = self::getConfig('files', false);
			
			if ($cache !== false || $files !== false)
				throw new \Exception('Can\'t install Files component twice');
			
			self::setConfig('cache', Files\Repository::createRepository()->getId());
			self::setConfig('files', Files\Repository::createRepository()->getId());
			self::writeConfig();
		}
		
		/**
		 * Get the menu items
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu() {
			if (!\forge\components\Identity::hasPermission('com.Files.Admin'))
				return null;
			
			$menu = new \forge\components\Admin\MenuItem('content', self::l('Content'));
			
			$menu->appendChild(new \forge\components\Admin\MenuItem(
				'files',
				self::l('Files'),
				'/admin/Files'
			));
			
			return $menu;
		}
		
		/**
		 * Get the repository for /cache
		 * @return Files\Repository
		 */
		static public function getCacheRepository() {
			return new Files\Repository(self::getConfig('cache'));
		}
		
		/**
		 * Get the repository for /files
		 * @return Files\Repository
		 */
		static public function getFilesRepository() {
			return new Files\Repository(self::getConfig('files'));
		}

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Files.Admin'))
				return null;

			$repo = self::getFilesRepository();
			$free = \forge\Strings::bytesize($repo->getSize());

			return \forge\components\Templates::display('components/Files/tpl/inc.infobox.php',array('free'=>$free));
		}
	}
