<?php
	/**
	* view.User.php
	* Copyright 2011-2013 Mattias Lindholm
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
						$confirmed = false;
					}

					echo \forge\components\Templates::display(
						array(
							'%T/page.confirmation.php',
							'components/Accounts/tpl/page.confirmation.php'
						),
						array(
							'confirmed' => $confirmed
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
					if (empty($_GET['key']))
						throw new \forge\HttpException('No key provided',
								\forge\HttpException::HTTP_BAD_REQUEST);

					try {
						$entry = new \forge\components\Accounts\db\LostPassword();
						$entry->key = $_GET['key'];
						$entry->select('key');
						
						if ($entry->until < time())
							throw new \Exception();
					}
					catch (\Exception $e) {
						throw new \forge\HttpException('The key requested could not be found',
								\forge\HttpException::HTTP_NOT_FOUND);
					}

					echo \forge\components\Templates::display(
						array(
							'%T/page.recover-password.php',
							'components/Accounts/tpl/page.recover-password.php'
						),
						array(
							'entry' => $entry
						)
					);
				break;

				// Let the client register a new account if neccessary
				case 'register':
					// Redirect logged in users away from this page
					if (\forge\components\Identity::isAuthenticated())
						\forge\components\SiteMap::redirect('/');

					// Show the registration
					echo \forge\components\Templates::display(
						array(
							'%T/Accounts/page.register.php',
							'components/Accounts/tpl/page.register.php'
						)
					);
				break;

				// Show the success page of the registration
				case 'register/success':
					// Show the page
					echo \forge\components\Templates::display(
						array(
							'%T/page.registered.php',
							'components/Accounts/tpl/page.registered.php'
						)
					);
				break;

				// View the settings page for the user's account
				case null:
					\forge\components\SiteMap::redirect('/identity/settings');
				break;
			}
		}
	}