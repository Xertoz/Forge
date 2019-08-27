<?php
	/**
	* class.Context.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* This class contains various helper functions for identifying the HTTP request
	*/
	class Context {
		/**
		 * Figure out wether or not the client is an Android device
		 * @return bool
		 */
		static public function isAndroid() {
			return (bool)strlen(strstr($_SERVER['HTTP_USER_AGENT'], 'Android'));
		}
		
		/**
		 * Figure out wether or not the client is an iPad device
		 * @return bool
		 */
		static public function isIpad() {
			return (bool)strlen(strstr($_SERVER['HTTP_USER_AGENT'], 'iPad'));
		}
		
		/**
		 * Figure out wether or not the client is an iPhone device
		 * @return bool
		 */
		static public function isIphone() {
			return (bool)strlen(strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone'));
		}
		
		/**
		 * Figure out wether or not the client is a mobile device
		 * @return bool
		 */
		static public function isMobile() {
			return (bool)preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']);
		}
	}