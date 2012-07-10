<?php
	/**
	* tbl_users_cookies.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Accounts\db;

	/**
	* Table definition of users
	* This table will contain one row per user and store some generic data in it
	*/
	class Cookie extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'users_cookies';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;

		/**
		* Account name
		* @var int
		*/
		public $account = 'Int';

		/**
		* Cookie expire date
		* @var int
		*/
		public $expire = 'Int';

		/**
		* Cookie salt value
		* @var string
		*/
		public $salt = 'TinyText';

		/**
		* Generate a salt and expire date before insertion
		*/
		protected function beforeInsert() {
			$this->__set('salt',\forge\String::randomize(16));
			$this->__set('expire',time()+30*24*3600);
		}

		/**
		* Update the cookie on save
		*/
		protected function beforeSave() {
			$this->__set('expire',time()+30*24*3600);
		}
	}