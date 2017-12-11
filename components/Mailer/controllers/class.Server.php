<?php
	/**
	* class.Server.php
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
	class Server extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Mailer.Admin');
			
			$smtp = \forge\Post::getBool('smtp', false);
			$hostname = \forge\Post::getString('hostname');
			$username = \forge\Post::getString('username');
			$password = \forge\Post::getString('password');

			// The host field is required if we use an SMTP server
			if ($smtp && empty($hostname))
				throw new \forge\HttpException('You must specify which SMTP server to use',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			// Update the settings!
			\forge\components\Mailer::setSMTP(
					$smtp,
					$hostname,
					$username,
					$password);
			
			// We're ok!
			self::setResponse(self::l('Mail settings were updated successfully!'), self::RESULT_OK);
		}
	}