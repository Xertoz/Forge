<?php
	/**
 * class.Bind.php
 * Copyright 2019 Mattias Lindholm
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */
	
	namespace forge\components\Identity\pages;

	use forge\components\Identity;
	use forge\components\SiteMap\Page;
	use forge\components\Templates;
	use forge\Get;
	use forge\HttpException;

	/**
	 * Let the user bind a new login type to the account
	 */
	class Bind extends Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'Identity: Bind';

		/**
		* Search engine disabled
		* @var string
		*/
		const SEO_ENABLE = false;

		/**
		 * View the page
		 * @param $page
		 * @param $vars
		 * @return string
		 * @throws \Exception
		 */
		public function view($page, $vars) {
			Identity::auth();

			$type = Get::getString('type');

			if ($type !== null)
				throw new HttpException('No type given', HttpException::HTTP_BAD_REQUEST);

			$target = null;
			foreach (Identity::getProviders() as $provider)
				if ($provider::getTitle() === $type)
					$target = $provider;
			if (!$target)
				throw new HttpException('Type not found', HttpException::HTTP_NOT_FOUND);

			foreach (Identity::getIdentity()->getProviders() as $provider)
				if (get_class($provider) === $target)
					throw new HttpException('Duplicate account types', HttpException::HTTP_CONFLICT);

			// Display the bind page
			return Templates::view('page_bind', ['provider' => $target::showBind()]);
		}
	}