<?php
	/**
	* class.Delete.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Websites\controllers;

	/**
	* Attempt a login to the system
	*/
	class Delete extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Websites.Admin');

			try {
				$website = new \forge\components\Websites\db\Website($_POST['id']);
				$website->delete();
			}
			catch (\Exception $e) {
				self::setResponse($e->getMessage(), self::RESULT_BAD);
			}
		}
	}