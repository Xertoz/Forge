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
			return self::getConfig('FROM_ADDRESS', 'noreply@'.$_SERVER['HTTP_HOST']);
		}
		
		/**
		 * Get the name which the system should send from
		 * @return string
		 */
		static public function getFromName() {
			return self::getConfig('FROM_NAME', 'Forge '.FORGE_VERSION);
		}
		
		/**
		 * Get the SMTP password
		 * @return string
		 */
		static public function getSMTPPassword() {
			return self::getConfig('SMTP_PASSWORD');
		}
		
		/**
		 * Get the SMTP hostname
		 * @return string
		 */
		static public function getSMTPServer() {
			return self::getConfig('SMTP_SERVER');
		}
		
		/**
		 * Get the SMTP username
		 * @return string
		 */
		static public function getSMTPUsername() {
			return self::getConfig('SMTP_SERVER');
		}
		
		/**
		 * Get wether or not to use SMTP
		 * @return bool
		 */
		static public function getSMTPUsage() {
			return self::getConfig('USE_SMTP', false);
		}

		/**
		* Check if given string is a proper e-mail address
		* @param string E-mail address
		* @return bool
		*/
		static public function isMail($string) {
			return (bool)preg_match('/^[A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/',$string);
		}
	}