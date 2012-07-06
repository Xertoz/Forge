<?php
    /**
    * class.Account.php
    * Copyright 2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Accounts\controllers;

    /**
    * Attempt a login to the system
    */
    class Account extends \forge\Controller {
    	/**
    	 * Process POST data
    	 * @return void
    	 */
    	public function process() {
            // We are either setting our own account, or have admin access
            if ($_REQUEST['account']['id'] != \forge\components\Accounts::getUserId())
                \forge\components\Accounts::Restrict('Accounts','admin','list','w');

            // Init the account we use to edit
            $account = new \forge\components\Accounts\db\Account($_REQUEST['account']['id']);
            
            // Delete the account?
            if (isset($_POST['delete'])) {
            	$account->delete();
            	self::setResponse(_('The account has been deleted!'), self::RESULT_OK);
            	
            	return;
            }

            // All required fields must be set
            if (empty($_REQUEST['account']['account']))
                throw new \forge\HttpException(_('You must specify an account name'),\forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_REQUEST['account']['email']))
                throw new \forge\HttpException(_('You must specify an e-mail address'),\forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_REQUEST['account']['name_first']))
                throw new \forge\HttpException(_('You must specify a first name'),\forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_REQUEST['account']['name_last']))
                throw new \forge\HttpException(_('You must specify a last name'),\forge\HttpException::HTTP_BAD_REQUEST);

            // Set the account settings
            $account->user_account = $_REQUEST['account']['account'];
            $account->user_email = $_REQUEST['account']['email'];
            $account->user_name_first = $_REQUEST['account']['name_first'];
            $account->user_name_last = $_REQUEST['account']['name_last'];

            // Save it
            $account->save();

            // If we're an admin, we should manage the permissions
            if (isset($_REQUEST['permissions'])) {
                \forge\components\Accounts::Restrict('Accounts','admin','list','w');

                // Remove any existant permissions
                foreach ($account->getPermissions() as $permission)
                    $permission->delete();

                // Find the new permission settings and store them
                foreach (\forge\components\Accounts::getDomains() as $domain => $list1)
                    foreach ($list1 as $category => $list2)
                        foreach ($list2 as $field)
                            if (isset($_REQUEST['permissions'][$domain][$category][$field])) {
                                $permission = new \forge\components\Accounts\db\Permissions();
                                $permission->user_id = $account->getId();
                                $permission->permission_domain = $domain;
                                $permission->permission_category = $category;
                                $permission->permission_field = $field;
                                $permission->permission_read = $_REQUEST['permissions'][$domain][$category][$field]['read'];
                                $permission->permission_write = $_REQUEST['permissions'][$domain][$category][$field]['write'];
                                $permission->insert();
                            }
            }
            
            self::setResponse(_('Account settings were saved!'), self::RESULT_OK);
    	}
    }