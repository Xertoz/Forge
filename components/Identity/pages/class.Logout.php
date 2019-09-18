<?php
	/**
	 * class.Logout.php
	 * Copyright 2019 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */
	
	namespace forge\components\Identity\pages;

	use forge\components\Identity;
	use forge\components\SiteMap\Page;
	use forge\components\Templates;

	/**
	 * Log out the user
	 */
	class Logout extends Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'Identity: Log out';

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
			// Log out the user
			Identity::logout();

			// Display the log out page
			return Templates::view('logout');
		}
	}