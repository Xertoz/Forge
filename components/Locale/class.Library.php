<?php
	/**
	* class.Library.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Locale;

	/**
	* Index locale entries
	*/
	class Library {
		use \forge\Configurable;

		/**
		 * Add a new locale
		 * @param string $locale Locale to add
		 * @return void
		 * @throws \Exception
		 */
		static public function addLocale($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to add an invalid locale'));

			if (in_array($locale, self::getLocales()))
				throw new \Exception(_('Trying to add an existing locale!'));

			// Write the locale
			self::setConfig($locale, [], true);
		}

		/**
		 * Build a locale
		 * @param string $locale Locale to build
		 * @return void
		 * @throws \Exception
		 */
		static public function build($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to build an invalid locale'));

			$file = FORGE_PATH.'/files/.locales/'.$locale.'/LC_MESSAGES/Forge.po';
			$build = '';
			$library = self::getLocale($locale);

			foreach ($library as $message => $translation)
				$build .= '# !'.PHP_EOL.'msgid "'.addslashes($message).'"'.PHP_EOL.'msgstr "'.addslashes($translation).'"'.PHP_EOL;

			file_put_contents($file, $build);

			require_once FORGE_PATH.'/components/Locale/api/php-mo.php';
			phpmo_convert($file);

			// Clear cache
			bindtextdomain('Forge', FORGE_PATH.'/files');
			bindtextdomain('Forge', FORGE_PATH.'/files/.locales');

			self::setConfig($locale.'.built', true, true);
		}


		/**
		 * Get the total number of messages in a locale
		 * @return int
		 */
		static public function getEntries($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to read an invalid locale'));

			return count(self::getConfig($locale, []));
		}

		/**
		 * Get an entire library of a locale
		 * @param string $locale Locale to get
		 * @return array
		 * @throws \Exception
		 */
		static public function getLocale($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to get an invalid locale'));

			// Write the locale
			return self::getConfig($locale, []);
		}

		/**
		 * Get a list of all locales
		 * @return array Available locales
		 */
		static public function getLocales() {
			$keys = self::getConfigKeys();
			$locales = [];

			foreach ($keys as $key)
				if (\forge\components\Locale::isLocale($key))
					$locales[] = $key;

			return $locales;
		}

		/**
		 * Get a list of all messages
		 * @return array Messages
		 */
		static public function getMessages() {
			return array_keys(self::getConfig('en_US', []));
		}

		/**
		 * Get the messages missing in a locale (compared to en_US)
		 * @return int
		 */
		static public function getMissing($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to read an invalid locale'));

			$missing = [];
			$en_US = self::getMessages();
			$$locale = array_keys(self::getLocale($locale));

			foreach ($en_US as $message)
				if (!in_array($message, $$locale))
					$missing[] = $message;

			return $missing;
		}

		/**
		 * Get the total number of messages missing in a locale (compared to en_US)
		 * @return int
		 */
		static public function getMissingEntries($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to read an invalid locale'));

			$missing = 0;
			$en_US = self::getMessages();
			$$locale = array_keys(self::getLocale($locale));

			foreach ($en_US as $message)
				if (!in_array($message, $$locale))
					++$missing;

			return $missing;
		}

		/**
		 * Find out wether a locale has been built into a gettext library
		 * @param string $locale Locale
		 * @return bool
		 */
		static public function isBuilt($locale) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to read an invalid locale'));

			return self::getConfig($locale.'.built', false);
		}

		/**
		 * Find out wether a message ever is used
		 * @param string $message Message
		 * @return bool
		 */
		static public function isMessage($message) {
			$en_US = self::getLocale('en_US');

			return in_array($message, $en_US);
		}

		/**
		 * Set an entire library of a locale
		 * @param string $locale Locale to write to
		 * $param array $library Library to write (array of Message => Translation)
		 * @return void
		 * @throws \Exception
		 */
		static public function setLocale($locale, $library) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to set to an invalid locale'));

			// Write the locale
			self::setConfig($locale, $library, true);
		}

		/**
		 * Set a (new or old) message in a locale
		 * @param $locale Locale to use
		 * @param $message Message from en_US
		 * @param $translation Translation in the locale
		 * @return void
		 */
		static public function setMessage($locale, $message, $translation) {
			if (!\forge\components\Locale::isLocale($locale))
				throw new \Exception(_('Trying to set to an invalid locale'));

			if (!self::isMessage($message))
				throw new \Exception(_('Trying to translate a non-existing message!'));

			$library = self::getLocale($locale);
			$library[$message] = $translation;

			self::setConfig($locale, $library);
			self::setConfig($locale.'.built', false);
			self::writeConfig();
		}

		/**
		 * Scan for all messages used and update the en_US library
		 * @return void
		 */
		static public function scanMessages() {
			$library = [];
			$scanner = function($directory) use (&$library, &$scanner) {
				foreach (glob($directory.'/*') as $subject) {
					if (is_dir($subject))
						$scanner($subject);
					elseif (substr($subject, strlen($subject)-4) == '.php')
						if (preg_match_all('/_\\(((?<![\\\\])[\'"])((?:.(?!(?<![\\\\])\\1))*.?)\\1\\)/', file_get_contents($subject), $matches))
							foreach ($matches[2] as $match)
								$library[$match] = $match;
				}
			};
			$scanner(FORGE_PATH);

			self::setLocale('en_US', $library);
		}
	}