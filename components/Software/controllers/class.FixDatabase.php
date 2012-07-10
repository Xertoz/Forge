<?php
	/**
	* class.Login.php
	* Copyright 2012 Mattias Lindholm
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
		 */
		public function process() {
			if (empty($_POST['name']))
				return self::setResponse('Account name or email must be supplied', self::RESULT_BAD);

			if (empty($_POST['type']))
				return self::setResponse('Account password must be supplied', self::RESULT_BAD);

			$class = 'forge\\'.($_POST['type'] == 'COM' ? 'components' : 'modules').'\\'.$_POST['name'];

			try {
				\forge\components\Databases::fixDatabase($_POST['name'], $_POST['type']);
				self::setCode(self::RESULT_OK);
			}
			catch (\forge\HttpException $e) {
				self::setResponse($e->getMessage(), self::RESULT_BAD);
			}
		}
	}