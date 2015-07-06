<?php
	/**
	* class.Blob.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\db;

	/**
	* A BLOB which holds file content
	*/
	class Blob extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'files_blob';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;
		
		/**
		 * SHA1 hash
		 * @var string
		 */
		public $hash = [
			'Char',
			'length' => 40
		];

		/**
		* Content size
		* @var int
		*/
		public $size = 'Int';
	}