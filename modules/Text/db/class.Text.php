<?php
	/**
	* tbl.textpages.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\modules\Text\db;

	/**
	* Definition of the data for each page in the site map
	*/
	class Text extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'textpages';

		/**
		* Page ID
		* @var int
		*/
		public $page_id = [
			'Integer',
			'index' => true
		];

		/**
		* Page ID
		* @var int
		*/
		public $mobile_enabled = 'Boolean';

		/**
		* Page contents
		* @var string
		*/
		public $text_content = 'Text';

		/**
		* Page contents
		* @var string
		*/
		public $mobile_content = 'Text';
	}