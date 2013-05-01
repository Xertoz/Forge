<?php
	/**
	* class.ConfigFile.php
	* Copyright 2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	/**
	 * A helper class for managing files on the virtual disk
	 */
	class ConfigFile extends File {
		/**
		 * Find the file in the virtual disk
		 * @param string $name
		 * @return string
		 */
		static protected function jail($name) {
			$parts = explode('/', $name);
			
			foreach ($parts as $key => $item)
				if ($item == '..')
					unset($parts[$key]);
			
			return FORGE_PATH.'/config/'.implode('/', $parts);
		}
	}