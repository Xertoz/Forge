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

	use forge\components\Admin\MenuItem;

	/**
	* File manager
	*/
	class Files extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Admin\InfoBox, \forge\components\Templates\RequireJS {
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
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Files.Admin'))
				return null;
			
			$menu = new MenuItem('files', self::l('Files'), '/'.$page->page_url.'/Files', 'ion ion-folder');
			
			if ($addon === '\\forge\\components\\Files')
				$menu->setActive();
			
			return $menu;
		}

		/**
		 * Get the repository for /cache
		 * @return Files\Repository
		 * @throws Databases\exceptions\NoData
		 */
		static public function getCacheRepository() {
			return new Files\Repository(self::getConfig('cache'));
		}

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 * @throws Databases\exceptions\NoData
		 * @throws \forge\HttpException
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Files.Admin'))
				return null;

			$nodes = new \forge\components\Databases\TableList([
				'type' => new \forge\components\Files\db\TreeNode,
				'where' => ['null:parent' => null]
			]);
			
			$size = 0;
			foreach ($nodes as $node)
				$size += Files\Repository::newFromNode($node)->getSize();
			$free = \forge\Strings::bytesize($size);

			return \forge\components\Templates::view('infobox', ['free' => $free]);
		}
		
		static public function getRequireJS() {
			$plugins = [];
			
			if (\forge\components\Identity::hasPermission('com.Files.Admin'))
				$plugins['files-admin'] = '/components/Files/script/files-admin';
			
			return $plugins;
		}
	}
