<?php
	/**
	* com.Files.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;
	use \forge\Component;

	/**
	* File manager
	*/
	class Files extends Component implements \forge\components\Dashboard\InfoBox {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = array(
			'Files' => array(
				'admin' => array(
					'use'
				)
			)
		);

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Accounts::getPermission(\forge\components\Accounts::getUserId(),'files','admin','use','r'))
				return null;

			$free = 0;

			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('files')) as $file)
				$free += $file->getSize();

			$free = \forge\String::bytesize($free);

			return \forge\components\Templates::display('components/Files/tpl/inc.infobox.php',array('free'=>$free));
		}
	}
