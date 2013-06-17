<?php
	/**
	* class.Admin.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Accounts;

	/**
	* Accounts component of Forge
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			\forge\components\Identity::restrict('com.Accounts.Admin');

			// Get all accounts
			$accounts = new \forge\components\Databases\ListMatrix(new \forge\components\Databases\Params([
				'type' => new \forge\components\Accounts\db\Account,
				'limit' => 10
			]));

			// Print the parsed template
			return \forge\components\Templates::display('components/Accounts/tpl/acp.accounts.php',['accounts'=>$accounts]);
		}

		static public function account() {
			\forge\components\Identity::restrict('com.Accounts.Admin');

			$account = new \forge\components\Accounts\db\Account(empty($_GET['id']) ? null : $_GET['id']);

			$tpl = array(
				'account' => $account,
				'domains' => \forge\components\Accounts::getDomains(),
				'permissions' => array()
			);

			foreach ($account->getPermissions() as $permission) {
				$tpl['permissions'][$permission->permission_domain][$permission->permission_category][$permission->permission_field]['read'] = $permission->permission_read;
				$tpl['permissions'][$permission->permission_domain][$permission->permission_category][$permission->permission_field]['write'] = $permission->permission_write;
			}

			return \forge\components\Templates::display('components/Accounts/tpl/acp.account.php',$tpl);
		}

		static public function lostpassword() {
			\forge\components\Identity::restrict('com.Accounts.Admin');

			// Print the parsed template
			return \forge\components\Templates::display('components/Accounts/tpl/acp.lostpassword.php',array(
				'lostpassword' => \forge\components\Accounts::config('lostpassword')
			));
		}

		static public function registration() {
			\forge\components\Identity::restrict('com.Accounts.Admin');

			$tpl = array(
				'activation' => \forge\components\Accounts::config('activation'),
				'confirmation' => \forge\components\Accounts::config('confirmation'),
				'registration' => \forge\components\Accounts::config('registration'),
				'thankyou' => \forge\components\Accounts::config('thankyou')
			);

			// Print the parsed template
			return \forge\components\Templates::display('components/Accounts/tpl/acp.registration.php',$tpl);
		}
	}