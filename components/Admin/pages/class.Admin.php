<?php
	/**
	* page.Admin.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\components\Admin\pages;

	/**
	* Display the administration
	*/
	class Admin extends \forge\components\SiteMap\Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'Administration';

		/**
		* Search engine disabled
		* @var string
		*/
		const SEO_ENABLE = false;
		
		protected $dynamic = true;

		/**
		* Create the page!
		* @var int Page id
		* @var array Form data
		* @return void
		* @throws Exception
		*/
		public function create($id,$page) {
			$page = new \forge\components\SiteMap\db\Page($id);
			$page->allowRemove = false;
			$page->write();
		}

		/**
		* View the page
		* @param \forge\components\SiteMap\db\Page Page
		* @param array Page vars
		* @return string
		* @throws Exception
		*/
		public function view($page, $vars) {
			$parts = explode('/',$vars['SUB_URI']);

			$addon = !empty($parts[0]) ? $parts[0] : 'Dashboard';
			$view = !empty($parts[1]) ? $parts[1] : 'index';

			return \forge\components\Admin::display($page, $addon, $view);
		}
	}