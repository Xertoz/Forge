<?php
	/**
	* class.LostPassword.php
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
	class LostPassword extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			if (empty($_POST['email']))
				throw new \forge\HttpException('You must supply an email address',
				\forge\HttpException::HTTP_BAD_REQUEST);
			
			$account = new \forge\components\Accounts\db\Account();
			$account->user_email = $_POST['email'];
			try {
				$account->select('user_email');
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('There is no account associated with that email address',
				\forge\HttpException::HTTP_BAD_REQUEST);
			}
			
			$lost = new \forge\components\Accounts\db\LostPassword();
			$lost->user = $account->getId();
			$lost->key = md5(\forge\Strings::randomize(32));
			$lost->until = time()+24*60*60;
			$lost->insert();
			
			$mail = new \forge\components\Mailer\Mail();
			$mail->AddAddress($account->user_email, $account->user_name_first.' '.$account->user_name_last);
			$mail->Subject = self::l('Lost password');
			$mail->Body = \forge\components\Accounts::getLostPasswordMessage($account, $lost);
			$mail->IsHTML();
			$mail->send();
			
			self::setResponse(self::l('We sent you an email containing information on how to proceed!'),
					self::RESULT_OK);
		}
	}