<?php
	/**
	* class.Admin.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap;

	/**
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function page() {
			\forge\components\Accounts::restrict('SiteMap','admin','pages','r');

			$entry = !empty($_GET['id']) ? new db\Page($_GET['id']) : new db\Page();
			$instance = $entry->page_type ? new $entry->page_type : null;

			return \forge\components\Templates::display('components/SiteMap/tpl/acp.page.php',array(
				'entry' => $entry,
				'instance' => $instance,
				'pages' => \forge\components\SiteMap::getAvailablePages(),
				'types' => \forge\components\SiteMap::getPageTypes()
			));
		}

		static public function index() {
			\forge\components\Accounts::restrict('SiteMap','admin','pages','r');
			
			$pages = new \forge\components\Databases\ListMatrix(new \forge\components\Databases\Params([
				'type' => new \forge\components\SiteMap\db\Page
			]));

			return \forge\components\Templates::display('components/SiteMap/tpl/acp.menu.php',[
				'pages' => $pages
			]);
		}
	}