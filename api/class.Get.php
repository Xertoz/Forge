<?php
	/**
	* class.Get.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;
	
	/**
	 * Helper class for handling GET data from HTTP requests
	 */
	class Get extends Input {
		static protected function getType() {
			return \INPUT_GET;
		}
	}