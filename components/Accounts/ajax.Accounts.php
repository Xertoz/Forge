<?php
	/**
	* ajax.Accounts.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Accounts;
	use \forge\components\Accounts;
	use \forge\HttpException;

	/**
	* Accounts AJAX plugin
	*/
	class Ajax extends \forge\components\XML\controllers\XML {

		/**
		* Send a link to a password reset page to the user
		*/
		static public function sendLostPasswordLink(\XMLWriter $xml) {
			// Make sure we have a valid email address
			if (empty($_POST['email']))
				throw new \forge\HttpException(_('No email address was set'), \forge\HttpException::HTTP_BAD_REQUEST);
			if (!\forge\components\Mailer::isMail($_POST['email']))
				throw new \forge\HttpException(_('An invalid email address was set'), \forge\HttpException::HTTP_BAD_REQUEST);

			// Select a user from the email address
			try {
				$user = new db\Account();
				$user->user_email = $_POST['email'];
				$user->select('user_email');
			}
			catch (\Exception $e) {
				throw new \forge\HttpException(_('No account with the given email address was found'), \forge\HttpException::HTTP_BAD_REQUEST);
			}

			// Create a key for the link
			$lost = new db\LostPassword();
			$lost->user = $user->getId();
			$lost->until = time()+24*3600;
			$lost->key = \forge\String::randomize(32);
			$lost->insert();

			// Get a few variables for use in the email body
			$tpl = array(
				'%account%' => $user->user_account,
				'%link%' => 'http://'.$_SERVER['SERVER_NAME'].'/user/recover-password?key='.urlencode($lost->key)
			);

			// Create & send the email
			$mail = new \forge\components\Mailer\Mail();
			$mail->AddAddress($user->user_email, $user->user_name_first.' '.$user->user_name_last);
			$mail->Subject = 'Lost password';
			$mail->Body = str_replace(array_keys($tpl), array_values($tpl), \forge\components\Accounts::config('lostpassword'));
			$mail->Send();

			// Tell the client we succeeded
			$xml->writeElement('lostpassword');
		}

		/**
		* Recover a password
		*/
		static public function recoverPassword(\XMLWriter $xml) {
			// Make sure we have valid fields
			if (empty($_POST['passwd1']) || empty($_POST['passwd2']))
				throw new \forge\HttpException(_('Both password fields must be set'), \forge\HttpException::HTTP_BAD_REQUEST);
			if ($_POST['passwd1'] != $_POST['passwd2'])
				throw new \forge\HttpException(_('The passwords don\'t match'), \forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_POST['key']))
				throw new \forge\HttpException(_('No key was posted'), \forge\HttpException::HTTP_BAD_REQUEST);

			// Select the request
			try {
				$lost = new db\LostPassword();
				$lost->key = $_POST['key'];
				$lost->select('key');
			}
			catch (\Exception $e) {
				throw new \forge\HttpException(_('No password recovery was found for the given key'), \forge\HttpException::HTTP_BAD_REQUEST);
			}

			// Get the user object
			$user = new db\Account($lost->user);
			$user->user_password = $user->hashPassword($_POST['passwd1']);
			$user->save();

			// Tell the client OK
			$xml->writeElement('recover');
		}
	}