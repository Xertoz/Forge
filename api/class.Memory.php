<?php
	/**
	* api.sessions.php
	* Copyright 2009-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	 * Helper class for storing data
	 *
	 */
	final class Memory {
		/**
		 * Recall or remember a cookie variable
		 * @param string Key
		 * @param string Value
		 * @return bool
		 * @throws \Exception
		 */
		static public function cookie() {
			switch (func_num_args()) {
				default:
					throw new \Exception('Invalid number of arguments');
	
				case 1:
					if (!isset($_COOKIE[(string)func_get_arg(0)]))
						return null;
					return $_COOKIE[(string)func_get_arg(0)];
	
				case 2:
					$_COOKIE[(string)func_get_arg(0)] = (string)func_get_arg(1);
					return setcookie((string)func_get_arg(0),(string)func_get_arg(1),time()+30*24*3600,'/');
			}
		}

		/**
		 * Recall or remember a session variable
		 * @param string Key
		 * @param mixed Value
		 * @return mixed
		 * @throws \Exception
		 */
		static public function session() {
			switch (func_num_args()) {
				default:
					throw new \Exception('Unknown number of arguments');
	
				case 1:
					if (!isset($_SESSION[(string)func_get_arg(0)]))
						return null;
					return $_SESSION[(string)func_get_arg(0)];
	
				case 2:
					return $_SESSION[(string)func_get_arg(0)] = func_get_arg(1);
			}
		}
	}
	
	// Start the session
	try { session_start(); } catch (\Exception $e) { }