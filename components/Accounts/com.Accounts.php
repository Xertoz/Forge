<?php
	/**
	* com.Accounts.php
	* Copyright 2009-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Account component
	*/
	class Accounts extends \forge\Component {
		use \forge\Configurable;
		
		/**
		* Minimum length of a password
		*/
		const MinimumPasswordLength = 4;

		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		* Set the current user ID
		* @param int ID
		* @return void
		*/
		static public function login($uid) {
			\forge\Memory::session('USER_AUTHENTICATION',(int)$uid);
		}
		
		/**
		 * Get the mail message to send for lost password requests
		 * @param \forge\components\Accounts\db\Account $account
		 * @param \forge\components\Accounts\db\LostPassword $lost
		 * @return type
		 */
		static public function getLostPasswordMessage(Accounts\db\Account $account,
		Accounts\db\LostPassword $lost) {
			return \forge\components\Templates::display([
				'%T/mail.lost-password.php',
				'components/Accounts/tpl/mail.lost-password.php'
			], [
				'account' => $account,
				'lost' => $lost
			]);
		}

		/**
		* Confirm a user account
		* @param int User ID
		* @param string Confirmation hash
		* @return bool
		* @throws Exception
		*/
		static public function confirm($id,$key) {
			$user = new \forge\components\Accounts\db\Account($id);

			if ($user->user_state == 'created' && $key==md5($user->user_password.$user->getID())) {
				$user->user_state = 'active';
				$user->save();

				return true;
			}
			elseif ($user->user_state == 'active')
				return true;

			return false;
		}

		/**
		* Create a new account
		* @param string Account name
		* @param string First name
		* @param string Last name
		* @param string E-mail address
		* @param string Password
		* @param string Password (confirm)
		*/
		static public function createAccount($account,$nameFirst,$nameLast,$email,$password,$passwordConfirm,$sendMail=true) {
			// Must have arguments.
			if (empty($account) || empty($nameFirst) || empty($nameLast) || empty($email) || empty($password) || empty($passwordConfirm))
				throw new \Exception('EMPTY_ARGUMENTS');

			// E-mail must be valid
			if (!Mailer::isMail($email))
				throw new \Exception('BAD_EMAIL');

			// Password must be OK
			if (strlen($password) < Accounts::MinimumPasswordLength)
				throw new \Exception('BAD_PASSWORD');
			if (md5($password) != md5($passwordConfirm))
				throw new \Exception('BAD_CONFIRM');

			// Look for duplicates
			$accountInstance = new \forge\components\Accounts\db\Account();
			$accountInstance->user_account = $account;
			$accountInstance->user_email = $email;
			try {
				$accountInstance->select('user_account');
			}
			catch (\Exception $e) {}
			if ($accountInstance->getId())
				throw new \forge\HttpException('There is already an account known by that name. Select something else!',
						\forge\HttpException::HTTP_CONFLICT);
			try {
				$accountInstance->select('user_email');
			}
			catch (\Exception $e) {}
			if ($accountInstance->getId())
				throw new \forge\HttpException('There is already an account using that email address. Use another one!',
						\forge\HttpException::HTTP_CONFLICT);
			
			// Create the account
			$accountInstance->user_state = 'created';
			$accountInstance->makeSalt();
			$accountInstance->user_name_first = $nameFirst;
			$accountInstance->user_name_last = $nameLast;
			$accountInstance->user_password = $accountInstance->hashPassword($password);
			$accountInstance->insert();

			// Mail the stuff to the new user
			if ($sendMail) {
				$domains = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
					'type' => new \forge\components\Websites\db\Website,
					'where' => array('alias'=>'')
				]));
				$sites = array();
				foreach ($domains as $i => $site)
					$sites[$i] = '<a href="http://'.$site->domain.'/">http://'.$site->domain.'/</a>';
				$sites = implode('<br>',$sites);
				$tpl = array(
					'%account%' => $account,
					'%name%' => $nameFirst.' '.$nameLast,
					'%email%' => $email,
					'%password%' => $password,
					'%sites%' => $sites,
					'%link%' => '<a href="'.($url='http://'.$_SERVER['SERVER_NAME'].'/user/confirm?id='.$accountInstance->getID().'&key='.md5($accountInstance->user_password.$accountInstance->getID())).'">'.$url.'</a>'
				);
				$mail = new \forge\components\Mailer\Mail();
				$mail->AddAddress($email,$nameFirst.' '.$nameLast);
				$mail->Subject = self::l('Account registered');
				$mail->Body = str_replace(array_keys($tpl),array_values($tpl),self::getRegisteredMessage());
				$mail->Send();
			}

			return $accountInstance;
		}
		
		/**
		 * Get the message to display when someone registers
		 * @return string
		 */
		static public function getRegisteredMessage() {
			return \forge\components\Templates::display([
				'%T/mail.registered.php',
				'components/Accounts/tpl/mail.registered.php'
			]);
		}
	}
	
	// If we have a cookie, utilize it.
	if (!is_null($uid = \forge\Memory::cookie('account')) && !is_null($password = \forge\Memory::cookie('password')))
		try {
		// Get the account in question
		$account = new \forge\components\Accounts\db\Account($uid);
		
		// Get all valid cookies associated with this account
		$cookies = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\Accounts\db\Cookie,
				'where' => array('account' => $uid)
				]));
		
		// Loop over the cookies and see if any matches the requested one
		foreach ($cookies as /** @var \forge\components\Accounts\db\tables\Cookie **/ $cookie)
			if ($cookie->expire < time())
				$cookie->delete();
			elseif (md5($account->user_password.$cookie->salt) == $password) {
				// Trigger an extension of the cookie
				$cookie->save();
				\forge\Memory::cookie('account',$uid);
				\forge\Memory::cookie('password',$password);
				
				// If this is the first access of this session, log us in!
				if (!Identity::isAuthenticated())
					Accounts\identities\Account::loginAccount($account->getID());
				
				break;
			}
		
		unset($cookies, $cookie, $account, $uid, $password);
	}
	catch (\Exception $e) {}