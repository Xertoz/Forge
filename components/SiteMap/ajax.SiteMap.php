<?php
	/**
	* ajax.SiteMap.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap;
	use \forge\components\SiteMap\db\tables\PageEntry;
	use \forge\components\SiteMap;
	use \forge\components\Accounts;
	use \forge\HttpException;

	/**
	* Component Site Map's AJAX callback
	*/
	class Ajax extends \forge\components\XML\controllers\XML {
		/**
		* Delete a page
		* @return string
		* @throws Exception
		*/
		static public function deletePage(\XMLWriter $xml) {
			$xml->writeElement('delete');
			$xml->writeAttribute('type','page');

			Accounts::restrict('SiteMap','admin','pages','w');

			if (!isset($_REQUEST['deletePage']['id']))
				throw new HttpException('NO_ID_SET',HttpException::HTTP_BAD_REQUEST);

			SiteMap::deletePage($_REQUEST['deletePage']['id']);
		}

		/**
		* Retrieve all pages on the website
		*/
		static public function pages(\XMLWriter $xml) {
			$xml->startElement('pages');

			Accounts::restrict('SiteMap','admin','pages','r');

			function __pages($parent,\XMLWriter $xml) {
				$pages = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
					'type' => new \forge\components\SiteMap\db\Page,
					'where' => array('page_parent'=>$parent),
					'order' => array('page_order'=>'ASC')
				]));

				foreach ($pages as /** @var \forge\components\SiteMap\db\tables\PageEntry **/ $page) {
					$xml->startElement('page');

					$xml->writeAttribute('id',$page->getID());
					$xml->writeElement('title',$page->page_title);
					$xml->writeElement('url','/'.$page->page_url);
					$xml->writeElement('publish',$page->page_publish);
					$xml->writeElement('menu',$page->page_menu);

					__pages($page->getID(),$xml);
					$xml->endElement();
				}
			}

			__pages(0,$xml);

			$xml->endElement();
		}

		/**
		* Sort the menu
		*/
		static public function sort(\XMLWriter $xml) {
			$xml->writeElement('sort');
			$xml->writeAttribute('type','pages');

			\forge\components\Databases::DB()->beginTransaction();

			try {
				foreach ($_POST['pages'] as $pid => $sort) {
					$page = new \forge\components\SiteMap\db\Page($pid);

					if ($page->page_parent != $_POST['parent'])
						throw new \Exception('Page has wrong parent');

					$page->page_order = $sort;

					$page->save();
				}
			}
			catch (\Exception $e) {
				\forge\components\Databases::DB()->rollBack();
				throw new \forge\HttpException('Could not sort the given pages',\forge\HttpException::HTTP_BAD_REQUEST);
			}

			\forge\components\Databases::DB()->commit();
		}
	}