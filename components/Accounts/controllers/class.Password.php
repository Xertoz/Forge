<?php
	/**
	* class.Password.php
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
	class Password extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function process() {
			\forge\components\Identity::auth();

			// Get the logged in identity and account provider, if any
			$identity = \forge\components\Identity::getIdentity();
			$provider = null;
			foreach ($identity->getProviders() as $item)
				if (get_class($item) == 'forge\\components\\Accounts\\identities\\Account')
					$provider = $item;

			if (is_null($provider))
				throw new \forge\HttpException('You can\'t set a password on a non-account', \forge\HttpException::HTTP_CONFLICT);
			$account = new \forge\components\Accounts\db\Account($provider->getId());

			if (empty($_POST['current']))
				throw new \forge\HttpException('You must provide your current password',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if ($account->user_password != $account->hashPassword($_POST['current']))
				throw new \forge\HttpException('Invalid current password',
						\forge\HttpException::HTTP_FORBIDDEN);
			if (empty($_POST['password1']))
				throw new \forge\HttpException('You must provide a new password',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (strlen($_POST['password1']) < \forge\components\Accounts::MinimumPasswordLength)
				throw new \forge\HttpException('Your new password is too short',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['password2']))
				throw new \forge\HttpException('You must confirm your new password',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if ($_POST['password1'] != $_POST['password2'])
				throw new \forge\HttpException('The two passwords differ',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			$account->user_password = $account->hashPassword($_POST['password1']);
			$account->save();
			
			self::setResponse(self::l('Your password was changed!'), self::RESULT_OK);
		}
	}