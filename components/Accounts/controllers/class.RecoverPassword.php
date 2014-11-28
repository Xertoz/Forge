<?php
	/**
	* class.RecoverPassword.php
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
	class RecoverPassword extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			if (empty($_POST['key']))
				throw new \forge\HttpException('No key provided',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			if (empty($_POST['password1']))
				throw new \forge\HttpException('No password provided',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (strlen($_POST['password1']) < \forge\components\Accounts::MinimumPasswordLength)
				throw new \forge\HttpException('The password is too short',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['password2']))
				throw new \forge\HttpException('You must confirm the password',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if ($_POST['password1'] != $_POST['password2'])
				throw new \forge\HttpException('The passwords differ',
						\forge\HttpException::HTTP_BAD_REQUEST);

			try {
				$entry = new \forge\components\Accounts\db\LostPassword();
				$entry->key = $_POST['key'];
				$entry->select('key');

				if ($entry->until < time())
					throw new \Exception();
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('The key requested could not be found',
						\forge\HttpException::HTTP_NOT_FOUND);
			}
			
			$account = new \forge\components\Accounts\db\Account($entry->user);
			$account->user_password = $account->hashPassword($_POST['password1']);
			$account->save();
			
			$entry->delete();
			
			\forge\components\SiteMap::redirect('/user/settings', 302);
		}
	}