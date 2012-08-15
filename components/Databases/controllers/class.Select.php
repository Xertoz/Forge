<?php
	/**
	* class.Select.php
	* Copyright 2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases\controllers;

	/**
	* Attempt a login to the system
	*/
	class Select extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Accounts::Restrict('Databases', 'admin', 'list', 'w');

			// We must set something
			if (empty($_POST['id']))
				throw new \forge\HttpException(_('Bad request'),
					\forge\HttpException::HTTP_BAD_REQUEST);

			// Set it
			\forge\components\Databases::setDefaultConnection($_POST['id']);
			
			self::setResponse(_('The connection is now used!'), self::RESULT_OK);
		}
	}