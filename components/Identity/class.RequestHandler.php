<?php
	/**
	* class.RequestHandler.php
	* Copyright 2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Identity;

	/**
	* The view for /identity
	*/
	class RequestHandler extends \forge\RequestHandler {
		/**
		* Handle the request
		*/
		public function handle() {
			$this->setContentType('text/html;charset=UTF-8');
			\forge\components\Templates::addStyle('<link href="/css/admin.css" rel="stylesheet" media="screen" />');

			switch ($this->getPath()) {
				default: throw new \forge\HttpException('Not Found',\forge\HttpException::HTTP_NOT_FOUND); break;

				case 'bind':
					\forge\components\Identity::auth();

					if (empty($_GET['type']))
						throw new \forge\HttpException(_('No type given'), \forge\HttpException::HTTP_BAD_REQUEST);

					$target = null;
					foreach (\forge\components\Identity::getProviders() as $provider)
						if ($provider::getTitle() == $_GET['type'])
							$target = $provider;
					if (!$target)
						throw new \forge\HttpException(_('Type not found'), \forge\HttpException::HTTP_NOT_FOUND);

					foreach (\forge\components\Identity::getIdentity()->getProviders() as $provider)
						if (get_class($provider) == $target)
							throw new \forge\HttpException(_('Duplicate account types'), \forge\HttpException::HTTP_CONFLICT);

					echo \forge\components\Templates::display(
						[
							'%T/Identity/page.bind.php',
							'components/Identity/tpl/page.bind.php'
						],
						[
							'provider' => $target
						]
					);
					break;

				// Show the login page
				case 'login':
					$forms = [];
					$fn = function() use (&$provider, &$forms) {$forms[] = $provider::showLogin(); };
					foreach (\forge\components\Identity::getProviders() as $provider)
						\forge\Helper::run($fn);

					if (\forge\components\Identity::isAuthenticated())
						\forge\components\SiteMap::redirect(!empty($_GET['from']) && $_GET['from'][0] == '/' ? $_GET['from'] : '/', 302);

					echo \forge\components\Templates::display(
						[
							'%T/Identity/page.login.php',
							'components/Identity/tpl/page.login.php'
						],
						[
							'forms' => $forms
						]
					);
				break;

				// Log out any user and show the logout page
				case 'logout':
					\forge\components\Identity::logout();
					echo \forge\components\Templates::display(
						array(
							'%T/Identity/page.logout.php',
							'components/Identity/tpl/page.logout.php'
						)
					);
				break;

				// View the settings page for the user's account
				case null:
					\forge\components\SiteMap::redirect('/identity/settings');
				break;
				case 'settings':
					\forge\components\Identity::auth();

					$identity = \forge\components\Identity::getIdentity();
					$providers = $identity->getProviders();
					foreach ($providers as &$provider)
						$provider = get_class($provider);

					echo \forge\components\Templates::display(
						array(
							'%T/Identity/page.settings.php',
							'components/Identity/tpl/page.settings.php'
						),
						array(
							'identity' => $identity,
							'providers' => $providers
						)
					);
				break;
			}
		}
	}