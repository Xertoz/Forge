<?php
	/**
	* class.Bind.php
	* Copyright 2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Accounts\controllers;

	/**
	* Bind an account to another identity
	*/
	class Bind extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \Exception
		 */
		public function process() {
			try {
				if (empty($_POST['account']))
					throw new \forge\HttpException('Account name must be supplied', \forge\HttpException::HTTP_BAD_REQUEST);

				if (empty($_POST['password']))
					throw new \forge\HttpException('Account password must be supplied', \forge\HttpException::HTTP_BAD_REQUEST);

				$account = \forge\components\Accounts\identities\Account::validate($_POST['account'], $_POST['password']);
				\forge\components\Identity::getIdentity()->bind(\forge\components\Accounts\identities\Account::getIdentity($account->getId()));

				\forge\components\SiteMap::redirect('/identity/settings', 302);
			}
			catch (\forge\HttpException $e) {
				self::setResponse($e->getMessage(), self::RESULT_BAD);
			}
		}
	}