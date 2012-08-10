<?php
	/**
	* com.Mailer.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Manage mails from (and to?) Forge
	*/
	class Mailer extends \forge\Component implements \forge\components\Dashboard\InfoBox {
		use \forge\Configurable;
		
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = array(
			'Mailer' => array(
				'admin' => array(
					'settings'
				)
			)
		);

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Accounts::getPermission(\forge\components\Accounts::getUserId(),'mailer','admin','settings','r'))
				return null;

			return \forge\components\Templates::display(
				'components/Mailer/tpl/inc.infobox.php'
			);
		}
		
		/**
		 * Get the address which the system should send from
		 * @return string
		 */
		static public function getFromAddress() {
			return self::getConfig('from.address', 'noreply@'.$_SERVER['HTTP_HOST']);
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
		 * @return string
		 */
		static public function setSender($name, $address) {
			self::setConfig('from.name', $name);
			self::setConfig('from.address', $address);
			self::writeConfig();
		}
		
		/**
		 * Set the SMTP info
		 * @return string
		 */
		static public function setSMTP($use=false, $hostname=null, $username=null, $password=null) {
			self::setConfig('smtp', (bool)$use);
			self::setConfig('smtp.hostname', $hostname);
			self::setConfig('smtp.username', $username);
			self::setConfig('smtp.password', $password);
			self::writeConfig();
		}
	}