<?php
	/**
	* class.Select.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Locale\controllers;

	/**
	* Select a locale for default use
	*/
	class Select extends \forge\Controller {
		/**
		 * Process POST data
		 * @throws \forge\HttpException
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Locale.Admin');

			if (empty($_POST['locale']) || !\forge\components\Locale::isLocale($_POST['locale']))
				throw new \forge\HttpException('No valid locale was entered.', \forge\HttpException::HTTP_BAD_REQUEST);

			try {
				\forge\components\Locale::setLocale($_POST['locale']);
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('An error occured when selecting the locale!',
					\forge\HttpException::HTTP_INTERNAL_SERVER_ERROR);
			}

			self::setResponse(self::l('Locale was successfully selected!'), self::RESULT_OK);
		}
	}