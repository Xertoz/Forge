<?php
	/**
	* class.Menu.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Admin;

	/**
	* Any addon who wish to append itself to the admin menu must implement this interface
	*/
	interface Menu {
		/**
		 * Get the menu items
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu();
	}