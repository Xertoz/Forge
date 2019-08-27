<?php
	/**
	* class.Translator.php
	* Copyright 2014-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\components\Locale;
	
	/**
	 * The translation engine
	 */
	trait Translator {
		/**
		 * Translate a string into the client's language
		 * @param string English input string
		 * @param string $sprintf Value(s) to pass into sprintf()
		 * @return string Localized string
		 */
		static public function l(string $string, ...$sprintf) {
			$i18n = \forge\components\Locale::getString($string);
			return count($sprintf) ? call_user_func_array('sprintf', array_merge([$i18n], $sprintf)) : $i18n;
		}
	}