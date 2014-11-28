<?php
	/**
	* class.Matrix.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\XML;

	/**
	* Objects implementing this class will be possible to send through XML
	*/
	abstract class Matrix {
		/**
		* Array of columns in this matrix
		* @var array
		*/
		protected $columns = array();
		
		/**
		 * Is this matrix draggable?
		 * @var bool
		 */
		private $draggable = false;

		/**
		* Array of the data in this matrix
		* @var array
		*/
		protected $rows = array();

		/**
		* Get the column data
		* @return array
		*/
		abstract public function getColumns();

		/**
		* Get the current page number
		* @return int
		*/
		abstract public function getPage();
		
		/**
		 * Return an array with all source items
		 * This method must return the items in the same ordered array as getRows
		 * @return array
		 */
		public function getItems() {
			return $this->getRows();
		}

		/**
		* Get the total available pages
		* @return int
		*/
		abstract public function getPages();

		/**
		* Get the total amount of rows available
		* @return int
		*/
		abstract public function getRows();

		/**
		* Navigate the paging to a specific page number
		* @param int Page number
		* @return void
		*/
		public function browse($page) {
			throw new \forge\HttpException('This table does not support paging',\forge\HttpException::HTTP_BAD_REQUEST);
		}

		/**
		* Draw the HTML table
		* @param array Columns to draw and their titles
		* @return string
		*/
		final public function drawTable($columns=array(),$stylize=array(), $attr=array()) {
			// If we have a specific page, get it.
			if (isset($_GET['page']))
				$this->browse((int)$_GET['page']);

			return \forge\components\Templates::display(
				'components/XML/tpl/inc.table.php',
				array(
					'attributes' => $attr,
					'columns' => $columns,
					'items' => $this->getItems(),
					'matrix' => $this,
					'rows' => $this->getRows(),
					'stylize' => $stylize
				)
			);
		}

		/**
		* Get the XML output
		* @param XMLWriter
		* @return void
		*/
		final public function getXML(\XMLWriter $xml) {
			// Start the root element
			$xml->startElement('matrix');
			$xml->writeAttribute('rows',$this->getRows());
			$xml->writeAttribute('page',$this->getPage());
			$xml->writeAttribute('pages',$this->getPages());

			// Give the column info
			$xml->startElement('columns');

			$xml->endElement();

			// We're finished with the root element
			$xml->endElement();
		}
		
		/**
		 * Is this matrix' rows draggable?
		 * @param bool $draggable If set, will change the draggable state
		 * @return bool The current (after any update) state
		 */
		final public function isDraggable($draggable=null) {
			if ($draggable !== null)
				$this->draggable = (bool)$draggable;
			
			return $this->draggable;
		}
	}