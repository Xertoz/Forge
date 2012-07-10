<?php
	/**
	* view.User.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Accounts;

	/**
	* The view for /user
	*/
	class UserHandler extends \forge\RequestHandler {
		/**
		* Handle the request
		*/
		public function handle() {
			$this->setContentType('text/html;charset=UTF-8');

			switch ($this->getPath()) {
				default: throw new \forge\HttpException('Not Found',\forge\HttpException::HTTP_NOT_FOUND);

				// Confirm a newly registered account
				case 'confirm':
					try {
						$confirmed = \forge\components\Accounts::confirm($_GET['id'],$_GET['key']);
					}
					catch (\Exception $e) {
						throw new \forge\HttpException(
							'The account could not be activated.',
							\forge\HttpException::HTTP_NOT_FOUND
						);
					}

					echo \forge\components\Templates::display(
						array(
							'%T/page.confirmation.php',
							'components/Accounts/tpl/page.confirmation.php'
						),
						array(
							'confirmed' => $confirmed,
							'message' => \forge\components\Accounts::config('activation')
						)
					);
				break;

				// Show the login page
				case 'login':
					$exception = false;
					if (\forge\Controller::getController() == 'Accounts\\Login' && \forge\Controller::getCode() == \forge\Controller::RESULT_OK)
						\forge\components\SiteMap::redirect(isset($_GET['from']) ? $_GET['from'] : '/', 302);

					echo \forge\components\Templates::display(
						array(
							'%T/page.login.php',
							'components/Accounts/tpl/page.login.php'
						),
						array(
							'account' => isset($_POST['account']) ? $_POST['account'] : null,
							'cookie' => isset($_POST['cookie']),
							'exception' => $exception
						)
					);
				break;

				// Log out any user and show the logout page
				case 'logout':
					\forge\components\Accounts::logout();
					echo \forge\components\Templates::display(
						array(
							'%T/page.logout.php',
							'components/Accounts/tpl/page.logout.php'
						)
					);
				break;

				// Show a form for sending a password recovery email
				case 'lost-password':
					echo \forge\components\Templates::display(
						array(
							'%T/page.lost-password.php',
							'components/Accounts/tpl/page.lost-password.php'
						)
					);
				break;

				// Show a form for resetting an account password
				case 'recover-password':
					$key = isset($_GET['key']) ? $_GET['key'] : null;

					if (!empty($key)) {
						try {
							$entry = new \forge\components\Accounts\db\LostPassword();
							$entry->key = $key;
							$entry->select('key');
						}
						catch (\Exception $e) {
							throw new \forge\HttpException('The key requested could not be found', \forge\HttpException::HTTP_NOT_FOUND);
						}
					}
					else
						$entry = null;

					echo \forge\components\Templates::display(
						array(
							'%T/page.lost-password.php',
							'components/Accounts/tpl/page.recover-password.php'
						),
						array(
							'entry' => $entry,
							'key' => $key
						)
					);
				break;

				// Let the client register a new account if neccessary
				case 'register':
					// Redirect logged in users away from this page
					if (\forge\components\Accounts::getUserId())
						\forge\components\SiteMap::redirect('/');

					// Register the user?
					$exception = false;
					if (count($_POST))
						try {
							\forge\components\Accounts::handleRegistration();
							\forge\components\SiteMap::redirect('/user/register/success', 307);
						}
						catch (\Exception $e) {
							$exception = $e;
						}

					// Show the registration
					echo \forge\components\Templates::display(
						array(
							'%T/page.register.php',
							'components/Accounts/tpl/page.register.php'
						),
						array(
							'exception' => $exception
						)
					);
				break;

				// Show the success page of the registration
				case 'register/success':
					// Show the page
					echo \forge\components\Templates::display(
						array(
							'%T/page.register.success.php',
							'components/Accounts/tpl/page.register.success.php'
						),
						array(
							'message' => \forge\components\Accounts::config('thankyou')
						)
					);
				break;

				// View the settings page for the user's account
				case null:
					\forge\components\SiteMap::redirect('/user/settings');
				break;
				case 'settings':
					\forge\components\Accounts::forceAuthentication();

					$account = \forge\components\Accounts::getUser();

					echo \forge\components\Templates::display(
						array(
							'%T/page.settings.php',
							'components/Accounts/tpl/page.settings.php'
						),
						array(
							'account' => $account
						)
					);
				break;
			}
		}
	}