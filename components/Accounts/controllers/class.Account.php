<?php
	/**
	* class.Account.php
	* Copyright 2012-2013 Mattias Lindholm
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
		 * @throws \forge\HttpException
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function process() {
			// Get the logged in identity and account provider, if any
			$identity = \forge\components\Identity::getIdentity();
			$provider = null;
			foreach ($identity->getProviders() as $item)
				if (get_class($item) == 'forge\\components\\Accounts\\identities\\Account')
					$provider = $item;

			// We are either setting our own account, or have admin access
			if ($provider && $_POST['account']['id'] == $provider->getId())
				$admin = \forge\components\Identity::hasPermission('com.Accounts.Admin');
			else {
				$admin = true;
				\forge\components\Identity::restrict('com.Accounts.Admin');
			}

			// Init the account we use to edit
			$account = new \forge\components\Accounts\db\Account($_POST['account']['id']);
			
			// Delete the account?
			// TODO: Implement delete functionality
			/*if (isset($_POST['delete'])) {
				$account->delete();
				self::setResponse(self::l('The account has been deleted!'), self::RESULT_OK);
				
				return;
			}*/

			// All required fields must be set
			if ($admin) {
				if (empty($_POST['account']['account']))
					throw new \forge\HttpException('You must specify an account name',\forge\HttpException::HTTP_BAD_REQUEST);
				if (empty($_POST['account']['email']))
					throw new \forge\HttpException('You must specify an e-mail address',\forge\HttpException::HTTP_BAD_REQUEST);
			}
			if (empty($_POST['account']['name_first']))
				throw new \forge\HttpException('You must specify a first name',\forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['account']['name_last']))
				throw new \forge\HttpException('You must specify a last name',\forge\HttpException::HTTP_BAD_REQUEST);

			// Set the account settings
			if ($admin) {
				$account->user_account = $_POST['account']['account'];
				$account->user_email = $_POST['account']['email'];
			}
			$account->user_name_first = $_POST['account']['name_first'];
			$account->user_name_last = $_POST['account']['name_last'];

			// Save it
			$account->save();
			self::setResponse(self::l('Account settings were saved!'), self::RESULT_OK);
		}
	}