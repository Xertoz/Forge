<?php
	/**
	* class.Scan.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Locale\controllers;

	/**
	* Scan en_US for messages
	*/
	class Scan extends \forge\Controller {
		/**
		 * Process POST data
		 * @throws \forge\HttpException
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Locale.Scan');

			try {
				\forge\components\Locale\Library::scanMessages();
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('An error occured during the scan!',
					\forge\HttpException::HTTP_INTERNAL_SERVER_ERROR);
			}

			self::setResponse(self::l('Messages were successfully scanned!'), self::RESULT_OK);
		}
	}