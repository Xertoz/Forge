<?php
	/**
	* com.Files.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* File manager
	*/
	class Files extends \forge\Component implements \forge\components\Dashboard\InfoBox {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Files.Admin'))
				return null;

			$free = 0;

			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('files')) as $file)
				$free += $file->getSize();

			$free = \forge\String::bytesize($free);

			return \forge\components\Templates::display('components/Files/tpl/inc.infobox.php',array('free'=>$free));
		}
	}
