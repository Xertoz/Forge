<?php
	/**
	* class.Settings.php
	* Copyright 2012-2013 Mattias Lindholm
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
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Mailer.Admin');

			// Update sender info
			if (empty($_REQUEST['from']['name']))
				throw new \forge\HttpException('You must provide a sender name',
						\forge\HttpException::HTTP_BAD_REQUEST);
			if (empty($_REQUEST['from']['address']))
				throw new \forge\HttpException('You must provide a sender address',
						\forge\HttpException::HTTP_BAD_REQUEST);
			\forge\components\Mailer::setSender($_REQUEST['from']['name'],
					$_REQUEST['from']['address']);

			// Update SMTP settings
			if (isset($_REQUEST['smtp']['use'])) {
				if (empty($_REQUEST['smtp']['hostname']))
					throw new \forge\HttpException('You have to specify which SMTP server to use',
							\forge\HttpException::HTTP_BAD_REQUEST);
				
				\forge\components\Mailer::setSMTP(true,
						$_REQUEST['smtp']['hostname'],
						$_REQUEST['smtp']['username'],
						$_REQUEST['smtp']['password']);
			}
			else
				\forge\components\Mailer::setSMTP(false,
						$_REQUEST['smtp']['hostname'],
						$_REQUEST['smtp']['username'],
						$_REQUEST['smtp']['password']);
			
			self::setResponse(self::l('Mail settings were updated successfully!'), self::RESULT_OK);
		}
	}