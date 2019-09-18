<?php
	/**
 * class.Login.php
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
	
	namespace forge\components\Identity\pages;

	use forge\components\Identity;
	use forge\components\SiteMap;
	use forge\components\SiteMap\Page;
	use forge\components\Templates;
	use forge\Get;

	/**
	 * Display a login page
	 */
	class Login extends Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'Identity: Log in';

		/**
		* Search engine disabled
		* @var string
		*/
		const SEO_ENABLE = false;

		/**
		 * View the page
		 * @param $page
		 * @param $vars
		 * @return string
		 * @throws \Exception
		 */
		public function view($page, $vars) {
			if (Identity::isAuthenticated())
				SiteMap::redirect(!empty($_GET['from']) && $_GET['from'][0] == '/' ? $_GET['from'] : '/', 302);

			$forms = [];
			foreach (Identity::getProviders() as $provider)
				$forms[] = $provider::showLogin();

			$view = substr(Get::getString('from'), 0, strlen('/admin')) === '/admin' ? 'forge-admin/login' : 'login';

			// Display the log out page
			return Templates::view($view, ['forms' => $forms]);
		}
	}