<?php
	/**
	* class.Input.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;
	
	/**
	* Helper class for handling user input from HTTP requests
	*/
	abstract class Input {
		/**
		* Run a filter on the input
		* @ignore
		* @param string $param Parameter name
		* @param int $filter Filter type
		* @param array $options Options
		* @return mixed
		*/
		static private function filter($param, $filter, $options=[]) {
			return filter_input(static::getType(), $param, $filter, $options);
		}
		
		/**
		* Get a boolean
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return bool|null
		*/
		static public function getBool($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_BOOLEAN);
			
			return is_bool($v) ? $v : $default;
		}
		
		/**
		* Get a list of booleans
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return bool|null
		*/
		static public function getBools($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_BOOLEAN, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
		
		/**
		* Get an email address
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getEmail($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_EMAIL);
			
			return is_string($v) ? $v : $default;
		}
		
		/**
		* Get a list of email addresses
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getEmails($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_EMAIL, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
		
		/**
		* Get a float
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return int|null
		*/
		static public function getFloat($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_FLOAT);
			
			return is_float($v) ? $v : $default;
		}
		
		/**
		* Get a list of floats
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return int|null
		*/
		static public function getFloats($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_FLOAT, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
		
		/**
		* Get an integer
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return int|null
		*/
		static public function getInt($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_INT);
			
			return is_int($v) ? $v : $default;
		}
		
		static public function getInts($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_INT, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
		
		/**
		* Get an IP
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getIP($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_IP);
			
			return is_string($v) ? $v : $default;
		}
		
		/**
		* Get a list of IPs
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getIPs($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_IP, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
		
		/**
		 * Get a string if it matches the regular expression
		 * @param string $param Parameter name
		 * @param string $expr Regular expression
		 * @param mixed $default Default value if input was invalid
		 * @return string|null
		 */
		static public function getRegexp($param, $expr, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => $expr]]);
			
			return is_string($v) ? $v : $default;
		}
		
		/**
		* Get a string
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getString($param, $default=null) {
			$v = self::filter($param, \FILTER_DEFAULT);
			
			return is_string($v) ? $v : $default;
		}
		
		/**
		* Get a list of strings
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getStrings($param, $default=null) {
			$v = self::filter($param, \FILTER_DEFAULT, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
		
		/**
		 * Internal function which determines where to fetch input from
		 * @ignore
		 * @return int
		 */
		static protected function getType() {
			return \INPUT_REQUEST;
		}
		
		/**
		* Get an URL
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getURL($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_URL);
			
			return is_string($v) ? $v : $default;
		}
		
		/**
		* Get a list of URLs
		* @param string $param Parameter name
		* @param mixed $default Default value if input was invalid
		* @return string|null
		*/
		static public function getURLs($param, $default=null) {
			$v = self::filter($param, \FILTER_VALIDATE_URL, \FILTER_REQUIRE_ARRAY);
			
			return is_array($v) ? $v : $default;
		}
	}