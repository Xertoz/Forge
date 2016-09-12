<?php
	/**
	* tbl.sitemap.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap\db;

	/**
	* Definition of the data for each page in the site map
	*/
	class Page extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'sitemap';

		/**
		* Page parent
		* @var int
		*/
		public $page_parent = 'Integer';

		/**
		* Publish page
		* @var int
		*/
		public $page_publish = array(
			'TinyInt',
			'length' => 1
		);

		/**
		* Assign to menu
		* @var int
		*/
		public $page_menu = array(
			'TinyInt',
			'length' => 1
		);

		/**
		* Front page
		* @var int
		*/
		public $page_default = array(
			'TinyInt',
			'length' => 1
		);

		/**
		* Page order within menu
		* @var int
		*/
		public $page_order = 'Integer';

		/**
		* Page creation date
		* @var string
		*/
		public $page_created = 'DateTime';

		/**
		* Page updated date
		* @var string
		*/
		public $page_updated = 'DateTime';

		/**
		* URL
		* @var string
		* @todo This should be UNIQUE coupled with forge_website
		*/
		public $page_url = [
			'Char',
			'length' => 255
		];

		/**
		* Page title
		* @var string
		*/
		public $page_title = 'TinyText';

		/**
		* Page type
		* @var string
		*/
		public $page_type = 'TinyText';

		/**
		* Meta description
		* @var string
		*/
		public $meta_description = 'TinyText';

		/**
		* Meta keywords
		* @var string
		*/
		public $meta_keywords = 'TinyText';

		/**
		* Update timestamps on insertion
		* @return void
		*/
		protected function beforeInsert() {
			$this->__set('page_created',time());
			$this->__set('page_updated',time());
		}

		/**
		* Update timestamps on update
		* @return void
		*/
		protected function beforeSave() {
			$this->__set('page_updated',time());
		}
		
		public function getChildren() {
			return new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new Page,
				'order' => ['page_order' => 'DESC'],
				'where' => ['page_parent' => $this->getId()]
			]));
		}
	}