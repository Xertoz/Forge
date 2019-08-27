<?php
	/**
	* class.Delete.php
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
	class Delete extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.SiteMap.Admin');
			
			try {
				if (empty($_POST['page']['id']))
					throw new \forge\HttpException('The ID must not be empty', \forge\HttpException::HTTP_BAD_REQUEST);
				
				\forge\components\Databases::DB()->beginTransaction();
				
				$entry = new \forge\components\SiteMap\db\Page($_POST['page']['id']);
				
				if (!$entry->allowRemove)
					throw new \forge\HttpException('The page is not allowed to be removed', \forge\HttpException::HTTP_FORBIDDEN);
				
				(new $entry->page_type)->delete($entry->getID());
				$entry->delete();
				
				\forge\components\Databases::DB()->commit();
			}
			catch (\Exception $e) {
				if (\forge\components\Databases::DB()->inTransaction())
					\forge\components\Databases::DB()->rollBack();
				
				throw $e;
			}
		}
	}