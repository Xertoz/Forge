<?php
	/**
	* class.Helper.php
	* Copyright 2012-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* This class contains various helper functions for OOP development
	*/
	class Helper {
		/**
		 * Call an anonymous function while suppressing all exceptions
		 * @param \Closure $function The function to be called
		 * @return mixed|void Returns the function return value
		 */
		static public function run(\Closure $function) {
			$arguments = func_get_args();
			array_shift($arguments);
			
			try {
				return call_user_func_array($function, $arguments);
			}
			catch (\Exception $e) {
				/* Catch all exceptions, but do nothing! */
			}
		}
	}