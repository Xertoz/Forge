<?php
	/**
	* class.Page.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap;

	/**
	* Component Site Map's page type definition
	*/
	abstract class Page {
		/**
		* Page type name
		* @var string
		*/
		protected $name = null;

		/**
		* Page type title
		* @var string
		*/
		protected $title = null;

		/**
		* Wether or not to allow dynamic URIs (handle sub pages in one parent page?)
		* @var bool
		*/
		protected $dynamic = false;

		/**
		* How often this is updated for sitemap.xml
		* @var string
		*/
		const SEO_ENABLE = true;

		/**
		* How often this is updated for sitemap.xml
		* @var string
		*/
		const SEO_FREQUENCY = 'weekly';

		/**
		* How often this is updated for sitemap.xml
		* @var string
		*/
		const SEO_PRIORITY = '1';

		/**
		 * @var \forge\components\SiteMap\db\Page
		 */
		protected $page;

		public function __construct(db\Page $page=null) {
			$this->page = $page;
		}

		/**
		 * Get page type title
		 * @return string
		 * @throws \Exception
		 */
		public function getTitle() {
			if (empty($this->title))
				throw new \Exception('Invalid page type');

			return $this->title;
		}

		/**
		* Get page type definition name
		* @return string
		*/
		public static function getName() {
			return get_called_class();
		}

		/**
		* Fetch this form's creation template
		* @return string
		*/
		public function getCreationForm() {
			return null;
		}

		/**
		* Find out wether or not to allow dynamic requests
		* @return bool
		*/
		public function isDynamic() {
			return (bool)$this->dynamic;
		}

		/**
		* Fetch the edit form
		* @param int Page id
		* @return string
		*/
		public function getEditForm($pageId) {
			return;
		}

		/**
		* Create the page!
		* @var int Page id
		* @var array Form data
		* @return void
		* @throws Exception
		*/
		public function create($pageId,$pageData) {
			return;
		}

		/**
		* Delete the page
		* @var int Page id
		* @return void
		* @throws Exception
		*/
		public function delete($pageId) {
			return;
		}

		/**
		* Edit the page
		* @var int Page id
		* @var array Page data
		* @return void
		* @throws Exception
		*/
		public function edit($pageId,$pageData) {
			return;
		}

		/**
		* View the page
		* @var db\Page Page
		* @var array Page params
		* @return string
		* @throws Exception
		*/
		abstract public function view($page,$vars);
	}