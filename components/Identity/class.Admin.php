<?php
	/**
	* class.Admin.php
	* Copyright 2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Identity;

	/**
	* Administer identities
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			\forge\components\Identity::restrict('com.Identity.Admin');

			// Print the parsed template
			return \forge\components\Templates::display(
				'components/Identity/tpl/acp.index.php',
				['identities' => \forge\components\Identity::getIdentities()]
			);
		}

		static public function view() {
			\forge\components\Identity::restrict('com.Identity.Admin');

			return \forge\components\Templates::display(
				'components/Identity/tpl/acp.view.php',
				[
					'identity' => new \forge\components\Identity\Identity($_GET['id']),
					'permissions' => \forge\components\Identity::getAllPermissions()
				]
			);
		}
	}