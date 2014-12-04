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
	class Files extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Dashboard\InfoBox {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];
		
		/**
		 * Get the menu items
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu() {
			if (!\forge\components\Identity::hasPermission('com.Files.Admin'))
				return null;
			
			$menu = new \forge\components\Admin\MenuItem('content', self::l('Content'));
			
			$menu->appendChild(new \forge\components\Admin\MenuItem(
				'files',
				self::l('Files'),
				'/admin/Files'
			));
			
			return $menu;
		}

		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Files.Admin'))
				return null;

			return \forge\components\Templates::display('components/Files/tpl/inc.infobox.php');
		}
	}
