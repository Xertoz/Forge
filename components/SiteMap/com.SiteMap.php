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
	class SiteMap extends \forge\Component implements \forge\components\Dashboard\InfoBox {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
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
		* @return array
		* @throws Exception
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
		* Create a new page
		* @param string Title
		* @param int Parent id
		* @param string URL
		* @param string Type
		* @param mixed Page
		* @return fcSmDbPage
		* @throws Exception
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
		* @throws Exception
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
		* @throws Exception
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
		* @return array
		* @throws Exception
		*/
		static public function getPageTypes() {
			$types = array();
			foreach (\forge\Addon::getModules() as $module)
				foreach (call_user_func('\forge\modules\\'.$module.'::getNamespace','pages') as $class)
					$types[] = new $class;
			return $types;
		}

		/**
		* Return a list of created pages
		* @return \forge\components\Databases\TableList
		* @throws Exception
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
		* @throws Exception
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
		* Check if page is parent of child
		* @param PageEntry Child
		* @param PageEntry Parent
		* @return bool
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
		* @throws Exception
		*/
		static public function getTitle($id) {
			$page = new \forge\components\SiteMap\db\Page($id);
			return $page->page_title;
		}

		/**
		* Get URI of page
		* @param int Page id
		* @return string
		* @throws Exception
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
		* @var mixed New location
		* @var int HTTP status code
		* @return void
		* @throws Exception
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