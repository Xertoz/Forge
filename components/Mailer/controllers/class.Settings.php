<?php
	/**
	* class.Settings.php
	* Copyright 2012-2017 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Mailer\controllers;

	/**
	* Handle page models through HTTP
	*/
	class Settings extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Mailer.Admin');

			// Get the name
			$name = \forge\Post::getString('name');
			if (empty($name))
				throw new \forge\HttpException('You must provide a sender name',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			// Get the address
			$address = \forge\Post::getString('address');
			if (empty($address))
				throw new \forge\HttpException('You must provide a sender address',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			// Set the new name & email
			\forge\components\Mailer::setSender($name, $address);
			
			// We're done!
			self::setResponse(self::l('Mail settings were updated successfully!'), self::RESULT_OK);
		}
	}