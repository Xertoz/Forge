<?php
	/**
	* tbl.lost_passwords.php
	* Copyright 2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Accounts\db;

	/**
	* Table definition of lost passwords
	*/
	class LostPassword extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'lost_passwords';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;

		/**
		* Account ID
		* @var int
		*/
		public $user = 'Integer';

		/**
		* This key will be usable until this unix timestamp
		* @var int
		*/
		public $until = 'Integer';

		/**
		* Key to use in the link
		* @var string
		*/
		public $key = array(
			'Char',
			'length' => 32
		);
	}