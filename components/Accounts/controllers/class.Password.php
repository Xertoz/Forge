<?php
	/**
	* class.Password.php
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
	class Password extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Accounts::forceAuthentication();
			
			$account = \forge\components\Accounts::getUser();

			if (empty($_POST['current']))
				throw new \forge\HttpException(_('You must provide your current password'),
						\forge\HttpException::HTTP_BAD_REQUEST);
			if ($account->user_password != $account->hashPassword($_POST['current']))
				throw new \forge\HttpException(_('Invalid current password'),
						\forge\HttpException::HTTP_FORBIDDEN);
			if (empty($_POST['password1']))
				throw new \forge\HttpException(_('You must provide a new password'),
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (strlen($_POST['password1']) < \forge\components\Accounts::MinimumPasswordLength)
				throw new \forge\HttpException(_('Your new password is too short'),
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['password2']))
				throw new \forge\HttpException(_('You must confirm your new password'),
						\forge\HttpException::HTTP_BAD_REQUEST);
			if ($_POST['password1'] != $_POST['password2'])
				throw new \forge\HttpException(_('The two passwords differ'),
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			$account->user_password = $account->hashPassword($_POST['password1']);
			$account->save();
			
			self::setResponse(_('Your password was changed!'), self::RESULT_OK);
		}
	}