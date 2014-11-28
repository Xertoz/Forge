<?php
	/**
	* class.Delete.php
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
	class Delete extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Databases.Admin');

			// We must set something
			if (empty($_POST['id']))
				throw new \forge\HttpException('Bad request',
					\forge\HttpException::HTTP_BAD_REQUEST);

			\forge\components\Databases::deleteConnection($_POST['id']);
			
			self::setResponse(self::l('The connection was deleted!'), self::RESULT_OK);
		}
	}