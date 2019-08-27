<?php
	/**
	* com.Documentation.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Database component
	*/
	class Documentation extends \forge\Component implements \forge\components\Admin\Menu {
		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu($page, $addon, $view) {
			$menu = new \forge\components\Admin\MenuItem('documentation', self::l('Documentation'), '/'.$page->page_url.'/Documentation', 'fa fa-book');
			
			if ($addon === '\\forge\\components\\Documentation')
				$menu->setActive();
			
			return $menu;
		}
	}