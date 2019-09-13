<?php
	/**
	* class.DataTable.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;

	use \forge\components\Templates;

	/**
	 * A table that can be outputted as HTML for AdminLTE
	 */
	class DataTable {
		/**
		 * @var array[string] The table attributes
		 */
		private $attributes = [];

		/**
		 * @var array[string=>function] The callbacks to be used
		 */
		private $callbacks = [];

		/**
		 * @var array[string] The output columns
		 */
		private $columns = [];

		/**
		 * @var bool Should the user be able to drag rows in this table?
		 */
		private $draggable = false;

		/**
		 * The iterable data
		 * @var \Iterator
		 */
		private $iterable;

		/**
		 * @var bool Should the table be paginated?
		 */
		private $paging = false;

		/**
		 * @var bool Should the user be able to sort columns in this table?
		 */
		private $sortable = false;

		/**
		 * @var bool Should the user be able to search in this table?
		 */
		private $searchable = false;

		/**
		 * Initiate the table from an iterable variable
		 * @param iterable $iterable Iterable variable
		 * @return void
		 */
		public function __construct(iterable $iterable) {
			$this->iterable = $iterable;
		}

		/**
		 * Getting this object as string is equal to calling ->draw()
		 * @return string
		 * @throws \forge\HttpException
		 */
		public function __toString() {
			return $this->draw();
		}

		/**
		 * Generate HTML table
		 * @param array $columns
		 * @param array $callbacks
		 * @param array $attributes
		 * @return string
		 * @throws \forge\HttpException
		 */
		public function draw(array $columns=[], array $callbacks=[], array $attributes=[]) {
			$this->columns = array_merge($this->columns, $columns);
			$this->callbacks = array_merge($this->callbacks, $callbacks);
			$this->attributes = array_merge($this->attributes, $attributes);

			return Templates::display('components/Templates/tpl/inc.datatable.php', [
				'attr' => $this->attributes,
				'callbacks' => $this->callbacks,
				'columns' => $this->columns,
				'iterable' => $this->iterable,
				'table' => $this
			]);
		}

		/**
		 * Check or set whether this table's rows are draggable
		 * @param bool $draggable
		 * @return bool
		 */
		public function isDraggable($draggable=null) {
			if (is_bool($draggable))
				$this->draggable = $draggable;

			return $this->draggable;
		}

		/**
		 * Check or set whether this table's rows are paginated
		 * @param bool $paging
		 * @return bool
		 */
		public function isPaging($paging=null) {
			if (is_bool($paging))
				$this->paging = $paging;

			return $this->paging;
		}

		/**
		 * Check or set whether this table is searchable
		 * @param bool $searchable
		 * @return bool
		 */
		public function isSearchable($searchable=null) {
			if (is_bool($searchable))
				$this->searchable = $searchable;

			return $this->searchable;
		}

		/**
		 * Check or set whether this table's rows are sortable
		 * @param bool $sortable
		 * @return bool
		 */
		public function isSortable($sortable=null) {
			if (is_bool($sortable))
				$this->sortable = $sortable;

			return $this->sortable;
		}

		public function setAttributes($attributes) {
			$this->attributes = $attributes;
		}

		public function setCallbacks($callbacks) {
			$this->callbacks = $callbacks;
		}

		public function setColumns($columns) {
			$this->columns = $columns;
		}
	}
