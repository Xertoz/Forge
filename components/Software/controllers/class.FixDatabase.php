<?php
	/**
	* class.FixDatabase.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Software\controllers;

	/**
	* Attempt a login to the system
	*/
	class FixDatabase extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Software.Admin');

			if (empty($_POST['name']))
				return self::setResponse('Account name or email must be supplied', self::RESULT_BAD);

			if (empty($_POST['type']))
				return self::setResponse('Account password must be supplied', self::RESULT_BAD);

			$class = 'forge\\'.($_POST['type'] == 'COM' ? 'components' : 'modules').'\\'.$_POST['name'];

			try {
				\forge\components\Databases::fixDatabase($_POST['name'], $_POST['type']);
				self::setResponse(self::l('The database was updated!'), self::RESULT_OK);
			}
			catch (\forge\HttpException $e) {
				self::setResponse($e->getMessage(), self::RESULT_BAD);
			}
		}
	}