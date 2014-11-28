<?php
	/**
	* class.Permissions.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Identity\controllers;

	/**
	* Handle permissions granted to an identity
	*/
	class Permissions extends \forge\Controller {
		/**
		 * Process POST data
		 * @throws \forge\HttpException
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Identity.Admin');

			if (empty($_POST['identity']['id']))
				throw new \forge\HttpException('No identity was selected for update', \forge\HttpException::HTTP_BAD_REQUEST);

			$identity = new \forge\components\Identity\Identity($_POST['identity']['id']);

			// Remove any existant permissions
			foreach ($identity->getPermissions() as $permission)
				$permission->delete();

			// Find the new permission settings and store them
			foreach (\forge\components\Identity::getAllPermissions() as $name)
				if (!empty($_POST['permissions'][$name])) {
					$permission = new \forge\components\Identity\db\Permission();
					$permission->identity = $identity->getId();
					$permission->permission = $name;
					$permission->insert();
				}

			self::setResponse(self::l('Identity permissions settings were saved!'), self::RESULT_OK);
		}
	}