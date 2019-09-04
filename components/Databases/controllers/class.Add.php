<?php
	/**
	* class.Add.php
	* Copyright 2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases\controllers;

	/**
	* Attempt a login to the system
	*/
	class Add extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Databases.Admin');

			// Require a type.
			if (empty($_POST['system']))
				throw new \forge\HttpException('You must choose a driver',
						\forge\HttpException::HTTP_BAD_REQUEST);

			// Require a hostname.
			if (empty($_POST['hostname']))
				throw new \forge\HttpException('You must supply a hostname',
						\forge\HttpException::HTTP_BAD_REQUEST);

			// Require a database.
			if (empty($_POST['database']))
				throw new \forge\HttpException('You must supply a database name',
						\forge\HttpException::HTTP_BAD_REQUEST);

			// Require a table prefix.
			if (empty($_POST['prefix']))
				throw new \forge\HttpException('You must supply a prefix',
						\forge\HttpException::HTTP_BAD_REQUEST);

			// Require a type.
			if (empty($_POST['username']))
				throw new \forge\HttpException('You must supply a username',
						\forge\HttpException::HTTP_BAD_REQUEST);

			// Require a type.
			if (empty($_POST['password']))
				throw new \forge\HttpException('You must supply a password',
						\forge\HttpException::HTTP_BAD_REQUEST);

			// Add it
			try {
				\forge\components\Databases::addConnection(
					$_POST['system'],
					$_POST['hostname'],
					$_POST['database'],
					$_POST['prefix'],
					$_POST['username'],
					$_POST['password']
				);
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('Could not create the connection: '.$e->getMessage(),
						\forge\HttpException::HTTP_BAD_REQUEST);
			}
			
			self::setResponse(self::l('The connection was set up!'), self::RESULT_OK);
		}
	}