<?php
	/**
	* com.Locale.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	use forge\components\Admin\MenuItem;

	/**
	* Locale component
	*/
	class Locale extends \forge\Component implements \forge\components\Admin\Menu {
		use \forge\Configurable;

		/**
		 * Currently selected locale
		 * @var string
		 */
		static private $locale = null;

		/**
		 * Permissions
		 * @var array
		 */
		static protected $permissions = ['Admin', 'Build', 'Scan'];

		/**
		 * Build a given locale
		 * @param string $locale Locale to build
		 * @return bool
		 * @throws \Exception
		 */
		static public function buildLocale($locale) {
			if (!self::isLocale($locale))
				throw new \Exception('Trying to build to an invalid locale');

			require_once FORGE_PATH.'/components/Locale/api/php-mo.php';

			$path = FORGE_PATH.'/files/.locales/'.$locale.'/LC_MESSAGES/';

			return phpmo_convert($path.'Forge.po', $path.'Forge.mo');
		}

		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Locale.Admin'))
				return null;
			
			$menu = new MenuItem('developer', self::l('Developer'));
			
			$menu->appendChild(new MenuItem(
				'locale',
				self::l('Locale'),
				'Locale'
			));
			
			return $menu;
		}

		/**
		 * Get the current locale
		 * @return string
		 */
		static public function getLocale() {
			return is_null(self::$locale) ? self::getConfig('locale', 'en_US') : self::$locale;
		}
		
		/**
		 * Attempt to get a string translated
		 * @param string $string
		 * @return string
		 */
		static public function getString($string) {
			return self::getConfig('locale.'.self::$locale.'.'.$string, $string);
		}

		/**
		 * Find out wether a given string is follows the locale format
		 * @param string $locale Test string
		 * @return bool Returns wether or not the string is of locale format
		 */
		static public function isLocale($locale) {
			return preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale);
		}

		/**
		 * Load a locale
		 * @param string $locale Locale to load. Null value loads configured locale.
		 * @return bool
		 * @throws \Exception
		 */
		static public function loadLocale($locale=null) {
			if ($locale == null)
				$locale = self::getLocale();

			if (!self::isLocale($locale))
				return false;

			// Load the locale
			self::setLocale($locale);

			return true;
		}

		/**
		 * Set a new locale
		 * @param string $locale New locale to set
		 * @param bool $load Load the new locale after setting it?
		 * @return void
		 * @throws \Exception
		 */
		static public function setLocale($locale, $load=false) {
			if (!self::isLocale($locale))
				throw new Exception('Trying to set to an invalid locale');

			self::setConfig('locale', $locale, true);

			if ($load)
				self::loadLocale($locale);
		}
	}