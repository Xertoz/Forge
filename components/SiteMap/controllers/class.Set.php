<?php
	/**
	* class.Set.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap\controllers;

	/**
	* Handle page models through HTTP
	*/
	class Set extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.SiteMap.Admin');
			
			try {
				if (empty($_POST['page']['title']))
					throw new \Exception(_('The title must not be empty'));
				
				if (empty($_POST['page']['url']))
					throw new \Exception(_('The URL must not be empty'));
				
				if (empty($_POST['page']['type']))
					throw new \Exception(_('The type must not be empty'));
				
				if (!($instance = new $_POST['page']['type']) instanceof \forge\components\SiteMap\Page)
					throw new \Exception(_('The requested page type was not a valid type'));
				
				\forge\components\Databases::DB()->beginTransaction();
			
				$entry = new \forge\components\SiteMap\db\Page($id=empty($_POST['page']['id'])?null:$_POST['page']['id']);
			
				$urlOriginal = $entry->page_url;
			
				$entry->page_title = $_POST['page']['title'];
				$entry->page_type = $_POST['page']['type'];
				$entry->page_url = $_POST['page']['url'];
				$entry->page_parent = empty($_POST['page']['parent'])?0:$_POST['page']['parent'];
				$entry->page_publish = empty($_POST['page']['publish'])?0:$_POST['page']['publish'];
				$entry->page_default = empty($_POST['page']['default'])?0:$_POST['page']['default'];
				$entry->page_menu = empty($_POST['page']['menu'])?0:$_POST['page']['menu'];
				$entry->meta_description = !isset($_POST['page']['meta_description'])?null:$_POST['page']['meta_description'];
				$entry->meta_keywords = !isset($_POST['page']['meta_keywords'])?null:$_POST['page']['meta_keywords'];
			
				$data = isset($_POST['plugin']) ? $_POST['plugin'] : null;
				
				$entry->write();
				
				if ($id)
					$instance->edit($entry->getId(),$data);
				else
					$instance->create($entry->getId(),$data);
			
				if (!empty($_POST['page']['default']))
					\forge\components\SiteMap::setDefaultPage($entry->getId());
				
				// Store some history if we renamed the URL
				if ($urlOriginal != $entry->page_url) {
					$history = new \forge\components\SiteMap\db\History();
					$history->url = $urlOriginal;
			
					// We either manipulate or insert the historic entry
					try {
						$history->select('url');
					}
					catch (\Exception $e) {
					}
			
					// Set it with the deleted status
					$history->http = \forge\HttpException::HTTP_MOVED_PERMANENTLY;
					$history->redirect = $entry->page_url;
					$history->write();
				}
			
				\forge\components\Databases::DB()->commit();
				
				\forge\components\SiteMap::redirect('/'.$entry->page_url, 302);
			}
			catch (\Exception $e) {
				if (\forge\components\Databases::DB()->inTransaction())
					\forge\components\Databases::DB()->rollBack();
				
				self::setResponse($e->getMessage(), self::RESULT_BAD);
				throw $e;
			}
		}
	}