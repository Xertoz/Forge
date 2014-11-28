<?php
	/**
	* class.Register.php
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
	class Register extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			if (empty($_POST['account']))
				throw new \forge\HttpException('You must specify an account name', \forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['email']))
				throw new \forge\HttpException('You must specify an e-mail address', \forge\HttpException::HTTP_BAD_REQUEST);
			if (!\forge\components\Mailer::isMail($_POST['email']))
				throw new \forge\HttpException('You must specify a proper e-mail address', \forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['name_first']))
				throw new \forge\HttpException('You must specify a first name', \forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['name_last']))
				throw new \forge\HttpException('You must specify a last name', \forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['password']))
				throw new \forge\HttpException('You must specify a password', \forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['password_confirm']))
				throw new \forge\HttpException('You must confirm the password', \forge\HttpException::HTTP_BAD_REQUEST);

			try {
				\forge\components\Accounts::createAccount(
					$_POST['account'],
					$_POST['name_first'],
					$_POST['name_last'],
					$_POST['email'],
					$_POST['password'],
					$_POST['password_confirm']
				);
			}
			catch (\Exception $e) {
				switch ($e->getMessage()) {
					default:
						throw $e;
					case 'EMAIL_ALREADY_REGISTERED':
						throw new \forge\HttpException('This e-mail address is already registered with us.',\forge\HttpException::HTTP_BAD_REQUEST);
					case 'ACCOUNT_ALREADY_REGISTERED':
						throw new \forge\HttpException('This account name is already registered with us.',\forge\HttpException::HTTP_BAD_REQUEST);
					case 'BAD_PASSWORD':
						throw new \forge\HttpException('The requested password was too short.',\forge\HttpException::HTTP_BAD_REQUEST);
					case 'BAD_CONFIRM':
						throw new \forge\HttpException('The passwords are not equal',\forge\HttpException::HTTP_BAD_REQUEST);
				}
			}
			
			\forge\components\SiteMap::redirect('/user/register/success', 302);
		}
	}