<?php
	/**
	* com.SiteMap.php
	* Copyright 2008-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;
	use \forge\components\SiteMap\Page;

	/**
	* Supply a site map sort of function to Forge. This component WILL handle URL translations etc.
	*/
	class SiteMap extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Dashboard\InfoBox, \forge\components\Templates\RequireJS {
		use \forge\Configurable;

		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin', 'Robots'];

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 * @throws Databases\exceptions\NoData
		 * @throws \forge\HttpException
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.SiteMap.Admin'))
				return null;

			$accounts = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\SiteMap\db\Page,
				'limit' => 1
			]));

			return \forge\components\Templates::display(
				'components/SiteMap/tpl/inc.infobox.php',
				array(
					'pages' => $accounts->getPages()
				)
			);
		}

		/**
		 * Get a list of pages at a desired level of the menu
		 * @param int Parent id
		 * @return Databases\TableList
		 * @throws Databases\exceptions\NoData
		 */
		static public function getMenu($parent=0) {
			$pages = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\SiteMap\db\Page,
				'where' => array('page_parent'=>$parent,'page_publish'=>1,'page_menu'=>1),
				'order' => array('page_order'=>'DESC')
			]));
			return $pages;
		}

		/**
		 * Get the current robot status
		 * @return bool
		 */
		static public function getRobots() {
			return self::getConfig('robots', false);
		}

		/**
		 * Set a new robot status
		 * @param bool $enabled
		 * @throws \Exception
		 */
		static public function setRobots($enabled) {
			self::setConfig('robots', (bool)$enabled);
			self::writeConfig();
		}

		/**
		 * Create a new page
		 * @param $title
		 * @param $parent
		 * @param $url
		 * @param $type
		 * @param $form
		 * @return SiteMap\db\Page
		 * @throws Databases\exceptions\NoData
		 */
		static public function createPage($title,$parent,$url,$type,$form) {
			if (empty($title) || empty($url) || empty($type))
				throw new \Exception('Invalid argument');

			if (!($pageInstance = new $type()) instanceof Page)
				throw new \Exception('Class is not of page type');

			\forge\components\Databases::DB()->beginTransaction();

			$page = new \forge\components\SiteMap\db\Page;

			try {
				$page->page_title = $title;
				$page->page_parent = $parent;
				$page->page_url = $url;
				$page->page_type = $type;
				$page->insert();

				$pageInstance->create($page->getID(),$form);
			}
			catch (\Exception $e) {
				\forge\components\Databases::DB()->rollBack();
				throw $e;
			}

			\forge\components\Databases::DB()->commit();

			return $page;
		}

		/**
		 * Delete a page
		 * @param int Page id
		 * @return void
		 * @throws \Exception
		 */
		static public function deletePage($pageId) {
			\forge\components\Databases::DB()->beginTransaction();

			try {
				// Delete the page
				$page = new \forge\components\SiteMap\db\Page($pageId);
				$type = $page->page_type;
				$type = new $type;
				$type->delete($pageId);
				$page->delete();

				// Store some history
				$history = new \forge\components\SiteMap\db\History();
				$history->url = $page->page_url;

				// We either manipulate or insert the historic entry
				try {
					$history->select('url');
				}
				catch (\Exception $e) {
					$history->insert();
				}

				// Set it with the deleted status
				$history->http = \forge\HttpException::HTTP_GONE;
				$history->save();
			}
			catch (\Exception $e) {
				\forge\components\Databases::DB()->rollBack();
				throw $e;
			}

			\forge\components\Databases::DB()->commit();
		}

		/**
		 * Set default page
		 * @param int Page id
		 * @return void
		 * @throws Databases\exceptions\NoData
		 */
		static public function setDefaultPage($id) {
			if (!$id = intval($id))
				throw new \Exception('INVALID_TYPE');

			$page = new \forge\components\SiteMap\db\Page();

			$page->page_default = true;
			$page->select('page_default');
			$page->page_default = false;
			$page->save();

			$page = new \forge\components\SiteMap\db\Page($id);
			$page->page_default = true;
			$page->save();
		}

		/**
		* Return a list of available page types
		* @return array[SiteMap\Page]
		* @throws Exception
		*/
		static public function getPageTypes() {
			$types = array();
			foreach (\forge\Addon::getAddons(true) as $addon)
				foreach (call_user_func($addon.'::getNamespace','pages') as $class)
					$types[] = new $class;
			return $types;
		}

		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return array[AdminMenu]|MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			$menus = [];
			
			if (\forge\components\Identity::hasPermission('com.SiteMap.Admin')) {
				$menu = new \forge\components\Admin\MenuItem('pages', self::l('Pages'), '/'.$page->page_url.'/SiteMap', 'fa fa-files-o');
				
				if ($addon === '\\forge\\components\\SiteMap' && ($view === 'index' || $view === 'page'))
					$menu->setActive();
				
				$menus[] = $menu;
			}
			
			if (\forge\components\Identity::hasPermission('com.SiteMap.Robots')) {
				$menus[] = new \forge\components\Admin\MenuItem('developer', self::l('Developer'));

				$menus[1]->appendChild(new \forge\components\Admin\MenuItem(
					'robots',
					self::l('Robots'),
					'SiteMap/robots'
				));
			}
			
			return $menus;
		}

		/**
		 * Return a list of created pages
		 * @return \forge\components\Databases\TableList
		 * @throws Databases\exceptions\NoData
		 */
		static public function getAvailablePages() {
			return new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\SiteMap\db\Page
			]));
		}

		/**
		 * Get all parents of page
		 * @param int Page ID
		 * @return array Parents
		 * @throws Databases\exceptions\NoData
		 */
		static public function getParents($id) {
			$parents = array();
			$page = new \forge\components\SiteMap\db\Page($id);

			while ($page->page_parent > 0) {
				$page = new \forge\components\SiteMap\db\Page($page->page_parent);
				$parents[] = $page;
			}

			return $parents;
		}

		/**
		 * Get the requirejs plugins
		 * @return array
		 * @throws \Exception
		 */
		static public function getRequireJS() {
			$plugins = [];

			if (\forge\components\Identity::hasPermission('com.SiteMap.Admin'))
				$plugins['forge.sitemap'] = '/components/SiteMap/script/forge.sitemap';

			return $plugins;
		}

		/**
		 * Check if page is parent of child
		 * @param PageEntry Child
		 * @param PageEntry Parent
		 * @return bool
		 * @throws Databases\exceptions\NoData
		 */
		static public function isParent($child,$parent) {
			$parents = self::getParents($child->page_id);

			foreach ($parents as $testsubject)
				if ($testsubject->page_id == $parent->page_id)
					return true;

			return false;
		}

		/**
		 * Get title of page
		 * @param int Page id
		 * @return string
		 * @throws Databases\exceptions\NoData
		 */
		static public function getTitle($id) {
			$page = new \forge\components\SiteMap\db\Page($id);
			return $page->page_title;
		}

		/**
		 * Get URI of page
		 * @param int Page id
		 * @return string
		 * @throws Databases\exceptions\NoData
		 */
		static public function getUri($id) {
			$page = new \forge\components\SiteMap\db\Page($id);
			return $page->page_url;
		}

		/**
		* Make an URI out of a string
		* @param string Source string
		* @return string
		*/
		static public function makeUri($source) {
			return preg_replace(
				array(
					'/(å|ä)/',
					'/(ö)/',
					'/\s+/',
					'/[^a-z0-9\-]/'
				),
				array(
					'a',
					'o',
					'-',
					null
				),
				strtolower($source)
			);
		}

		/**
		 * Go to a new location (redirect the visitor)
		 *
		 * @param $target
		 * @param int $http
		 * @return void
		 * @throws \Exception
		 */
		static public function redirect($target,$http=307) {
			if (!is_string($target))
				throw new \Exception('Target is not of string type');

			header('Location: '.$target,true,$http);
			die();
		}

		/**
		* Is the given string a valid URL?
		* @param string Test subject
		* @return bool
		*/
		static public function isURL($url) {
			return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
		}
	}