<?php
	/**
	* class.Organize.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap\controllers;

	/**
	* Handle page deletion through HTTP
	*/
	class Organize extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 */
		public function process() {
			\forge\components\Identity::restrict('com.SiteMap.Admin');

			\forge\components\Databases::DB()->beginTransaction();

			try {
				$order = count($_POST['menu']);
				
				foreach ($_POST['menu'] as $id) {
					$page = new \forge\components\SiteMap\db\Page($id);

					$page->page_order = --$order;

					$page->save();
				}
			}
			catch (\Exception $e) {
				\forge\components\Databases::DB()->rollBack();
				throw new \forge\HttpException('Could not sort the given pages',\forge\HttpException::HTTP_BAD_REQUEST);
			}

			\forge\components\Databases::DB()->commit();
			
			self::setResponse(self::l('The menu order was updated!'), self::RESULT_OK);
		}
	}