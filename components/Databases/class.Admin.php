<?php
	/**
	* class.Admin.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* Software component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		/**
		* Show the connection list
		*/
		static public function index() {
			\forge\components\Identity::restrict('com.Databases.Admin');

			return \forge\components\Templates::display(
				'components/Databases/tpl/acp.index.php',
				array(
					'databases' => \forge\components\Databases::getDatabaseList(),
					'default' => \forge\components\Databases::getDefaultConnection(),
					'drivers' => \forge\components\Databases::getDrivers()
				)
			);
		}
	}