<?php
	/**
	* page.Repository.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\components\Files\pages;

	/**
	* Manage a collection of files in folders, just like a hard drive.
	*/
	class Repository extends \forge\components\SiteMap\Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'File Repository';

		/**
		* Search engine index priority
		* @var string
		*/
		const SEO_PRIORITY = 0;
		
		protected $dynamic = true;

		/**
		* Get creation form
		* @return string
		*/
		//public function getCreationForm() {
			//return \forge\components\Templates::display('modules/Text/tpl/inc.create.php');
		//}

		/**
		* Get edit form
		* @return string
		*/
		/*public function getEditForm($id) {
			$text = new \forge\modules\Text\db\Text();
			$text->page_id = $id;
			$text->select('page_id');
			return \forge\components\Templates::display('modules/Text/tpl/inc.edit.php',array('text'=>$text));
		}*/

		/**
		* Perform edit
		* @param int Page id
		* @param array Page data
		* @return void
		* @throws Exception
		*/
		/*public function edit($pageId,$pageData) {
			$text = new \forge\modules\Text\db\Text();
			$text->page_id = $pageId;
			$text->select('page_id');
			$text->text_content = $pageData;
			$text->save();
		}*/

		/**
		* Create the repository!
		* @var int Page id
		* @var array Form data
		* @return void
		* @throws Exception
		*/
		public function create($id,$page) {
			$repo = \forge\components\Files\Repository::createRepository();
			$repo->link = $id;
			$repo->insert();
		}

		/**
		* Delete the page
		* @var int Page id
		* @return void
		* @throws Exception
		*/
		/*public function delete($id) {
			$page = new \forge\modules\Text\db\Text();
			$page->page_id = $id;
			try {
				$page->select('page_id');
				$page->delete();
			}
			catch (\Exception $e) {

			}
		}*/

		/**
		* View the page
		* @param int Page id
		* @param array Page vars
		* @return string
		* @throws Exception
		*/
		public function view($id,$vars) {
			$repo = \forge\components\Files\Repository::loadLink($id);
			
			if ($vars['SUB_URI'] !== false) {
				try {
					$node = $repo->getFolder($vars['SUB_URI']);
					$repo = $node;
				}
				catch (\forge\components\Files\exceptions\FileNotFound $e) {
					throw new \forge\HttpException('File not found', \forge\HttpException::HTTP_NOT_FOUND);
				}
			}
			
			$url = '/'.(empty($vars['SUB_URI']) ? $vars['PAGE_URI'] : $vars['PAGE_URI'].'/'.$vars['SUB_URI']);
			
			if ($repo->isFolder())
				return \forge\components\Templates::display(
					'components/Files/tpl/page.repo.php',
					['url' => $url, 'repo' => $repo]
				);
			else {
				try {
					$file = $repo->getFile();
					$file->passthru();
				}
				catch (\forge\components\Files\exceptions\FileNotFound $e) {
					throw new \forge\HttpException('File not found', \forge\HttpException::HTTP_NOT_FOUND, $e);
				}
			}
		}
	}