<?php
	/**
	* class.Message.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Locale\controllers;

	/**
	* Add or set a message in a locale
	*/
	class Message extends \forge\Controller {
		/**
		 * Process POST data
		 * @throws \forge\HttpException
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Locale.Admin');

			if (empty($_POST['locale']) || !\forge\components\Locale::isLocale($_POST['locale']))
				throw new \forge\HttpException(_('No valid locale was entered.'), \forge\HttpException::HTTP_BAD_REQUEST);

			if (empty($_POST['message']))
				throw new \forge\HttpException(_('No message was entered.'), \forge\HttpException::HTTP_BAD_REQUEST);

			if (empty($_POST['translation']))
				throw new \forge\HttpException(_('No translation was entered.'), \forge\HttpException::HTTP_BAD_REQUEST);

			try {
				\forge\components\Locale\Library::setMessage($_POST['locale'], $_POST['message'], $_POST['translation']);
			}
			catch (\Exception $e) {
				throw new \forge\HttpException(_('An error occured when writing the message!'),
					\forge\HttpException::HTTP_INTERNAL_SERVER_ERROR);
			}

			self::setResponse(_('Message was successfully set!'), self::RESULT_OK);
		}
	}