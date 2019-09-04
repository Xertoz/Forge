<?php
	/**
	* com.Mailer.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	use forge\components\Admin\MenuItem;

	/**
	* Manage mails from (and to?) Forge
	*/
	class Mailer extends \forge\Component implements \forge\components\Admin\Menu {
		use \forge\Configurable;
		
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Mailer.Admin'))
				return null;
			
			$menu = new MenuItem('developer', self::l('Developer'));
			
			$menu->appendChild(new MenuItem(
				'mailer',
				self::l('E-mail'),
				'Mailer'
			));
			
			return $menu;
		}
		
		/**
		 * Get the address which the system should send from
		 * @return string
		 */
		static public function getFromAddress() {
			$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
			return self::getConfig('from.address', 'noreply@'.$host);
		}
		
		/**
		 * Get the name which the system should send from
		 * @return string
		 */
		static public function getFromName() {
			return self::getConfig('from.name', 'Forge '.FORGE_VERSION);
		}
		
		/**
		 * Get the SMTP password
		 * @return string
		 */
		static public function getSMTPPassword() {
			return self::getConfig('smtp.password');
		}
		
		/**
		 * Get the SMTP hostname
		 * @return string
		 */
		static public function getSMTPServer() {
			return self::getConfig('smtp.hostname');
		}
		
		/**
		 * Get the SMTP username
		 * @return string
		 */
		static public function getSMTPUsername() {
			return self::getConfig('smtp.username');
		}
		
		/**
		 * Get wether or not to use SMTP
		 * @return bool
		 */
		static public function getSMTPUsage() {
			return self::getConfig('smtp', false);
		}

		/**
		* Check if given string is a proper e-mail address
		* @param string E-mail address
		* @return bool
		*/
		static public function isMail($string) {
			return (bool)preg_match('/^[A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/',$string);
		}

		/**
		 * Set the from field
		 * @param $name
		 * @param $address
		 * @return string
		 * @throws \Exception
		 */
		static public function setSender($name, $address) {
			self::setConfig('from.name', $name);
			self::setConfig('from.address', $address);
			self::writeConfig();
		}

		/**
		 * Set the SMTP info
		 * @param bool $use
		 * @param null $hostname
		 * @param null $username
		 * @param null $password
		 * @return string
		 * @throws \Exception
		 */
		static public function setSMTP($use=false, $hostname=null, $username=null, $password=null) {
			self::setConfig('smtp', (bool)$use);
			self::setConfig('smtp.hostname', $hostname);
			self::setConfig('smtp.username', $username);
			self::setConfig('smtp.password', $password);
			self::writeConfig();
		}
	}