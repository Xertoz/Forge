<?php
	/**
	 * class.Trashable.php
	 * Copyright 2014 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge\components\Databases;

	/**
	 * Adds a trash function to Table items
	 */
	trait Trashable {
		/**
		 * Flag determines wether or not the item is in the trash
		 * @var bool
		 */
		public $trashed = [
			'Boolean',
			'default' => '\'0\''
		];

		/**
		 * Is this item in the trash?
		 * @return bool
		 */
		public function isTrash() {
			return $this->__get('trashed');
		}

		/**
		 * Move or remove the item from/to the trash
		 * @param bool $trash Trash status to set
		 */
		public function trash($trash=true) {
			$this->__set('trashed', (bool)$trash);

			if ($this->getId())
				$this->save();
		}
	}