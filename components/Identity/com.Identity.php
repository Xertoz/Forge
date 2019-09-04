<?php
	/**
	* com.Identity.php
	* Copyright 2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Manage different user account types with a unified identification
	*/
	class Identity extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Dashboard\InfoBox {
		use \forge\Configurable;

		/**
		 * @var null|\forge\components\Identity\Identity The currently logged in identity
		 */
		static private $identity = null;

		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		 * Explicitly force the user to be authenticated by possibly redirecting to the login process
		 */
		static public function auth() {
			if (!self::isAuthenticated())
				\forge\components\SiteMap::redirect('/identity/login?from='.urlencode($_SERVER['REQUEST_URI']));
		}

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!self::getIdentity()->hasPermission('com.Identity.Admin'))
				return null;

			return Templates::display(
				'components/Identity/tpl/inc.infobox.php',
				[
					'total' => (new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
						'type' => new \forge\components\Identity\db\Identity(),
						'limit' => 1,
						'where' => ['master' => 0]
					])))->getPages()
				]
			);
		}

		/**
		 * Get a list of identities
		 * @param array $where
		 * @param array $order
		 * @param int $limit
		 * @param int $page
		 * @return Identity\Identity[]
		 */
		static public function getIdentities($where=[], $order=[], $limit=25, $page=1) {
			$where['master'] = 0;
			/** @var \forge\components\Identity\db\Identity[] $rows  */
			$rows = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\Identity\db\Identity(),
				'limit' => $limit,
				'page' => $page,
				'where' => $where,
				'order' => $order
			]));

			$identities = [];
			foreach ($rows as $row) {
				$identities[] = new Identity\Identity($row->getId());
			}

			return $identities;
		}

		/**
		 * Get the currently logged in identity if any
		 * @return Identity\Identity|null
		 */
		static public function getIdentity() {
			if (!self::isAuthenticated())
				return null;

			try {
				if (self::$identity === null)
					self::$identity = new \forge\components\Identity\Identity(\forge\Memory::session('identity'));

				return self::$identity;
			}
			catch (\Exception $e) {
				self::logout();
				return null;
			}
		}

		/**
		 * Get all available providers
		 * @return Identity\Provider[]
		 */
		static public function getProviders() {
			$providers = [];

			foreach (\forge\Addon::getAddons(true) as $addon)
				foreach ($addon::getNamespace('identities') as $provider)
					$providers[] = $provider;

			return $providers;
		}

		/**
		 * Check if the current user has a certain permission
		 * @param string $permission
		 * @return bool
		 */
		static public function hasPermission($permission) {
			if (!self::isAuthenticated())
				return false;

			try {
				return self::getIdentity()->hasPermission($permission);
			}
			catch (\Exception $e) {
				return false;
			}
		}

		/**
		 * Get all available grantable permissions
		 * @return string[]
		 */
		static public function getAllPermissions() {
			$permissions = [];
			foreach (\forge\Addon::getAddons(true) as $addon)
				$permissions = array_merge($permissions, $addon::getPermissions());
			return $permissions;
		}
		
		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Identity.Admin'))
				return null;
			
			$menu = new \forge\components\Admin\MenuItem('identity', self::l('People'), '/'.$page->page_url.'/Identity', 'ion ion-ios-people');
			
			if ($addon === '\\forge\\components\\Identity')
				$menu->setActive();
			
			return $menu;
		}

		/**
		 * Is the logged in user an administrator?
		 * @return bool
		 */
		static public function isAdmin() {
			try {
				\forge\components\Identity::restrict('com.Admin.Admin');

				return true;
			}
			catch (\Exception $e) {
				return false;
			}
		}

		/**
		 * Is the user logged in?
		 * @return bool
		 */
		static public function isAuthenticated() {
			return \forge\Memory::session('identity') !== null;
		}

		/**
		 * Check wether or not the user is a developer
		 * @return bool
		 */
		static public function isDeveloper() {
			return sha1(\forge\Memory::cookie('developer')) === self::getConfig('developer');
		}

		/**
		 * Login to an identity
		 * @param Identity\Identity $identity
		 */
		static public function login(Identity\Identity $identity) {
			\forge\Memory::session('identity', $identity->getId());
		}

		/**
		 * Log out of any identity
		 * @return void
		 */
		static public function logout() {
			foreach (self::getProviders() as $provider)
				$provider::logout();

			\forge\Memory::session('identity', null);
		}

		/**
		 * Restrict access to users with a specific permission
		 * @param $permission
		 * @return void
		 * @throws \forge\HttpException
		 */
		static public function restrict($permission) {
			if (!self::hasPermission($permission))
				throw new \forge\HttpException('Forbidden', \forge\HttpException::HTTP_FORBIDDEN);
		}

		/**
		 * Set a new developer key
		 * @param $key string Developer key
		 * @return void
		 */
		static public function setDeveloperKey($key) {
			self::setConfig('developer', sha1($key));
			self::writeConfig();
		}
	}
