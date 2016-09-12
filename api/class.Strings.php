<?php
	/**
	* api.strings.php
	* Copyright 2008-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;
	
	class Strings {
		/**
		* Returns a random string
		* @param int length
		* @return string
		*/
		static public function randomize($len) {
			$c = array(
				'a','b','c','d','e','f','g','h','i','j','k',
				'l','m','n','o','p','q','r','s','t','u','v',
				'w','x','y','z'
			);
			$s = null;
			for ($n=0;$n<$len;$n++)
				$s .= (rand(0,2)>0) ? ((rand(0,2)==1) ? strtoupper($c[rand(0,sizeof($c)-1)]) : $c[rand(0,sizeof($c)-1)]) : rand(0,9);
			return $s;
		}
	
		/**
		* Shorten a string to a certain length (appends ... on shortening)
		* @param string String
		* @param int Maximum length
		* @return string
		*/
		static public function shorten($String,$Length) {
			// Avoid bugs
			if (strlen($String) <= $Length)
				return $String;
	
			// Shorten it.
			$Short = substr($String,0,$Length);
	
			// Truncate it
			for ($i=strlen($Short)-1;$i>0;$i--)
				if ($String[$i] == ' ') {
					$Short = substr($String,0,strlen($Short)-(strlen($Short)-$i)).'...';
					if (strlen($Short) > $Length)
						$Short = \forge\Strings::shorten($Short,$Length);
					return $Short;
				}
	
			// Return the shortened
			return $Short;
		}
	
		/**
		* Convert given bytes into a proper size
		* @param mixed Byte number to convert
		* @return string Converted size
		* @throws Exception
		*/
		static public function bytesize($number) {
			if (!is_numeric($number))
				throw new \Exception('Argument 1 is not expected number');
	
			$n = 0;
	
			while ($number > 1024) {
				$number /= 1024;
				$n++;
			}
	
			switch ($n) {
				default: $size = '?b'; break;
				case 0: $size = 'b'; break;
				case 1: $size = 'kb'; break;
				case 2: $size = 'Mb'; break;
				case 3: $size = 'Gb'; break;
				case 4: $size = 'Tb'; break;
				case 5: $size = 'Pb'; break;
				case 6: $size = 'Eb'; break;
				case 7: $size = 'Zb'; break;
				case 8: $size = 'Yb'; break;
			}
	
			return round($number,2).' '.$size;
		}
	}