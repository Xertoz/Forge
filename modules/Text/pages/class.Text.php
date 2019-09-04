<?php
	/**
	* page.Text.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\modules\Text\pages;

	/**
	* A text page definition
	*/
	class Text extends \forge\components\SiteMap\Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'Text Page';

		/**
		* Search engine index priority
		* @var string
		*/
		const SEO_PRIORITY = 0.5;

		/**
		 * Get creation form
		 * @return string
		 * @throws \forge\HttpException
		 */
		public function getCreationForm() {
			return \forge\components\Templates::display('modules/Text/tpl/inc.create.php');
		}

		/**
		 * Get edit form
		 * @param $id
		 * @return string
		 * @throws \forge\HttpException
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function getEditForm($id) {
			$text = new \forge\modules\Text\db\Text();
			$text->page_id = $id;
			$text->select('page_id');
			return \forge\components\Templates::display('modules/Text/tpl/inc.edit.php',array('text'=>$text));
		}

		/**
		 * Perform edit
		 * @param $pageId
		 * @param $pageData
		 * @return void
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function edit($pageId,$pageData) {
			$text = new \forge\modules\Text\db\Text();
			$text->page_id = $pageId;
			$text->select('page_id');
			$text->mobile_enabled = !empty($pageData['mobile_enabled']);
			$text->mobile_content = $pageData['mobile_content'];
			$text->text_content = $pageData['text_content'];
			$text->save();
		}

		/**
		 * Create the page!
		 *
		 * @param $id
		 * @param $page
		 * @return void
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function create($id,$page) {
			$text = new \forge\modules\Text\db\Text();
			$text->page_id = $id;
			$text->mobile_enabled = !empty($page['mobile_enabled']);
			$text->mobile_content = $page['mobile_content'];
			$text->text_content = $page['text_content'];
			$text->insert();
		}

		/**
		 * Delete the page
		 *
		 * @return void
		 * @throws \forge\components\Databases\exceptions\NoData
		 * @var int Page id
		 */
		public function delete($id) {
			$page = new \forge\modules\Text\db\Text();
			$page->page_id = $id;
			try {
				$page->select('page_id');
				$page->delete();
			}
			catch (\Exception $e) {

			}
		}

		/**
		 * View the page
		 * @param $id
		 * @param $vars
		 * @return string
		 * @throws \forge\HttpException
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function view($id,$vars) {
			$page = new \forge\modules\Text\db\Text();
			$page->page_id = $id;
			$page->select('page_id');
			return \forge\components\Templates::display(
				array(
					'%T/page.text.php',
					'modules/Text/tpl/page.text.php'
				),
				array(
					'text'=>$page
				)
			);
		}
	}