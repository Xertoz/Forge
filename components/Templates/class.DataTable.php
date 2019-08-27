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
		 * The iterable data
		 * @var \Iterator
		 */
		private $iterable;

		/**
		 * Initiate the table from an iterable variable
		 * @param iterable $iterable Iterable variable
		 * @return void
		 */
		public function __construct(iterable $iterable) {
			$this->iterable = $iterable;
		}

		/**
		 * Generate HTML table
		 * @param array[string=>string] $columns List of columns to display (dataSource => columnTitle)
		 * @param array[string=>function] $callbacks List of callbacks for some columns (dataSource -> callback)
		 * @param array[string=>mixed] $attributes List of attributes for this table
		 */
		public function draw(array $columns, array $callbacks=[], array $attributes=[]) {
			return Templates::display('components/Templates/tpl/inc.datatable.php', [
				'attr' => $attributes,
				'callbacks' => $callbacks,
				'columns' => $columns,
				'iterable' => $this->iterable
			]);
		}
	}
