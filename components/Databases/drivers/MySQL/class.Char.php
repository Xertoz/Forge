<?php
	/**
	* mysql.Char.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases\drivers\MySQL;

	/**
	* MySQL data type Text
	*/
	class Char extends \forge\components\Databases\Type {
		/**
		* Default value
		*/
		protected $default = 'NULL';
		
		/**
		* Default column length
		*/
		protected $length = 255;

		/**
		* SQL type
		* @var string
		*/
		protected $type = 'char';
	}