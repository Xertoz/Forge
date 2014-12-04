<?php
	/**
	* class.JSON.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\components\Files;
	
	class JSON {
		static public function getTotalFileSize() {
			\forge\components\Identity::restrict('com.Files.Admin');

			$free = 0;

			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('files')) as $file)
				$free += $file->getSize();

			$free = \forge\String::bytesize($free);
			
			return $free;
		}
	}