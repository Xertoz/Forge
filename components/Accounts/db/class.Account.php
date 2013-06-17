<?php
	/**
	* class.Account.php
	* Copyright 2009-2013 Mattias Lindholm
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
	class Account extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'users';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;

		/**
		* Account state
		* @var int
		*/
		public $user_state = array(
			'Char',
			'length' => 7
		);

		/**
		* Account name
		* @var string
		*/
		public $user_account = array(
			'Char',
			'length' => 32,
			'unique' => true
		);

		/**
		* First name
		* @var string
		*/
		public $user_name_first = 'TinyText';

		/**
		* Last name
		* @var string
		*/
		public $user_name_last = 'TinyText';

		/**
		* E-mail address
		* @var Text
		*/
		public $user_email = array(
			'Char',
			'length' => 255,
			'unique' => true
		);

		/**
		* Account password
		* @var string
		*/
		public $user_password = array(
			'Char',
			'length' => 64
		);

		/**
		* Salt to be used when hashing the password
		*/
		public $user_salt = array(
			'Char',
			'length' => 8,
		);

		/**
		* Hash a password for the given user
		* @param string Message
		* @return string Digest
		*/
		public function hashPassword($message) {
			return hash('sha256', $this->__columns['user_salt']->get().$message);
		}

		/**
		* Make a new salt for this account
		* @return void
		*/
		public function makeSalt() {
			$this->__columns['user_salt']->set(\forge\String::randomize(8));
		}
	}