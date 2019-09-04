<?php
	/**
	* class.Login.php
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
	class Login extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function process() {
			if (empty($_POST['account']))
				return self::setResponse('Account name must be supplied', self::RESULT_BAD);

			if (empty($_POST['password']))
				return self::setResponse('Account password must be supplied', self::RESULT_BAD);

			$cookie = !isset($_POST['cookie']) ? false : (bool)$_POST['cookie'];

			try {
				\forge\components\Accounts\identities\Account::login($_POST['account'], $_POST['password'], $cookie);
				self::setCode(self::RESULT_OK);
			}
			catch (\forge\HttpException $e) {
				self::setResponse($e->getMessage(), self::RESULT_BAD);
			}
		}
	}