<?php
	/**
	* class.Permission.php
	* Copyright 2009-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Identity\db;

	/**
	* Table definition of user permissions
	*/
	class Permission extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'permissions';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;

		/**
		* User id
		* @var int
		*/
		public $identity = 'Int';

		/**
		* Permission domain
		* @var string
		*/
		public $permission = 'TinyText';
	}