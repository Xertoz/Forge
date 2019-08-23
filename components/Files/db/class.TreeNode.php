<?php
	/**
	* class.TreeNode.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\db;

	/**
	* A single node in the tree which joins files into a repository
	*/
	class TreeNode extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'files_tree';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;

		/**
		* Parent node
		* @var int
		*/
		public $parent = [
			'Foreign',
			'model' => 'Files.TreeNode'
		];

		/**
		* Page ID
		* @var int
		*/
		public $link = [
			'Foreign',
			'model' => 'SiteMap.Page'
		];

		/**
		* Total size of this node's content
		* @var int
		*/
		public $size = 'Integer';
		
		/**
		 * Referencing BLOB, if any
		 * @var int
		 */
		public $blob = [
			'Foreign',
			'model' => 'Files.Blob'
		];
		
		/**
		 * Creation date
		 * @var string
		 */
		public $created = 'DateTime';
		
		/**
		 * Update date
		 * @var string
		 */
		public $updated = 'DateTime';
		
		/**
		 * Foler name
		 * @var string
		 */
		public $name = 'TinyText';
		
		/**
		 * Run this before insertion
		 */
		protected function beforeInsert() {
			$now = date('Y-m-d H:i:s');
			$this->__set('created', $now);
			$this->__set('updated', $now);
		}
		
		/**
		 * Run this before updating
		 */
		protected function beforeSave() {
			$this->__set('updated', date('Y-m-d H:i:s'));
		}
	}