<?php
	/**
	* tbl.history.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap\db;

	/**
	* Each entry represents a historic page, that has once existed,
	* and contains info on what happened.
	*/
	class History extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'sitemap_history';

		/**
		* Page title
		* @var DataType
		*/
		public $http = 'Int';

		/**
		* URL
		* @var string
		*/
		public $url = 'TinyText';

		/**
		* Page type
		* @var string
		*/
		public $redirect = 'TinyText';
	}