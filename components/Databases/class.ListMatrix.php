<?php
	/**
	* class.ListMatrix.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* Make the TableList object into a Matrix
	*/
	class ListMatrix extends \forge\components\XML\Matrix {
		/**
		* Take the construct parameters into the TableList object
		* @param Params Parameters
		* @return void
		*/
		public function __construct(Params $params) {
			$reflection = new \ReflectionClass('forge\components\Databases\TableList');
			if (isset($_GET['page']))
				$params->page = (int)$_GET['page'];
			$this->rows = $reflection->newInstanceArgs(array($params));
		}

		/**
		* Navigate to a specific page
		* @param int Page
		* @return void
		*/
		public function browse($page) {
			$this->rows->browse($page);
		}
		
		/**
		 * Return an array with all source items
		 * This method must return the items in the same ordered array as getRows
		 * @return array
		 */
		public function getItems() {
			return $this->rows;
		}

		/**
		* Get the current page number
		* @return int
		*/
		public function getPage() {
			return $this->rows->getPage();
		}

		/**
		* Get the total available pages
		* @return int
		*/
		public function getPages() {
			return $this->rows->getPages();
		}

		/**
		* Get the total amount of rows available
		* @return int
		*/
		public function getRows() {
			$array = array();

			foreach ($this->rows as $row) {
				$current = array();

				foreach ($row->getColumns() as $column)
					$current[$column] = $row->$column;

				$array[] = $current;
			}

			return $array;
		}

		/**
		* Get the column info
		* @return array
		*/
		public function getColumns() {
			return $this->rows->getColumns();
		}
	}