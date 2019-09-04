<?php
	/**
	 * class.Account.php
	 * Copyright 2013 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge\components\Accounts\identities;

	/**
	 * Provider for internal Forge accounts
	 */
	class Account extends \forge\components\Identity\Provider {
		/**
		 * @var \forge\components\Accounts\db\Account
		 */
		private $account;

		public function __construct($identifier) {
			parent::__construct($identifier);
			$this->account = new \forge\components\Accounts\db\Account($identifier);
		}

		/**
		 * Attempt a login to an account
		 * @param string $username
		 * @param string $password
		 * @param bool $cookie
		 * @throws \forge\HttpException
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		static public function login($username, $password, $cookie=false) {
			// Validate the login credentials
			$account = self::validate($username, $password);

			// Save us as logged on!
			$identity = self::createIdentity($account->getId());
			\forge\components\Identity::login($identity);

			// Do we want to be remembered?
			if ((bool)$cookie === true) {
				$entry = new \forge\components\Accounts\db\Cookie();
				$entry->account = $account->getId();
				$entry->insert();

				\forge\Memory::cookie('account',$account->getId());
				\forge\Memory::cookie('password',md5($account->user_password.$entry->salt));
			}
		}

		/**
		 * Login an account by its id
		 * @param int $id
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		static public function loginAccount($id) {
			$account = new \forge\components\Accounts\db\Account($id);
			$identity = self::createIdentity($account->getId());
			\forge\components\Identity::login($identity);
		}

		public function getEmail() {
			return $this->account->user_email;
		}

		public function getName() {
			return $this->account->user_name_first.' '.$this->account->user_name_last;
		}

		static public function getTitle() {
			return 'Account';
		}

		static public function logout() {
			\forge\Memory::cookie('account', '');
			\forge\Memory::cookie('password', '');
		}

		public function showAdmin() {
			return \forge\components\Templates::display(['components/Accounts/tpl/adm.info.php'], ['account'=>$this->account]);
		}

		static public function showBind() {
			return \forge\components\Templates::display(['components/Accounts/tpl/inc.bind.php']);
		}

		static public function showLogin() {
			return \forge\components\Templates::display(['components/Accounts/tpl/inc.login.php']);
		}

		public function showSettings() {
			return \forge\components\Templates::display(['components/Accounts/tpl/inc.settings.php'], ['account'=>$this->account]);
		}

		/**
		 * Validate login credentials and return the account
		 * @param string $username
		 * @param string $password
		 * @return \forge\components\Accounts\db\Account
		 * @throws \forge\HttpException
		 */
		static public function validate($username, $password) {
			// Attempt reading the account (throws exception if it doesn't exist)
			try {
				$account = new \forge\components\Accounts\db\Account();
				$account->user_account = $username;
				$account->select('user_account');
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('Account does not exist', \forge\HttpException::HTTP_FORBIDDEN);
			}

			if ($account->user_state != 'active')
				throw new \forge\HttpException('The user is not activated, and cannot be logged into.', \forge\HttpException::HTTP_FORBIDDEN);

			// Throw exception if it's the wrong password
			if ($account->user_password != $account->hashPassword($password))
				throw new \forge\HttpException(sprintf(self::l('Wrong password given for user %s'), $username), \forge\HttpException::HTTP_FORBIDDEN);

			return $account;
		}
	}