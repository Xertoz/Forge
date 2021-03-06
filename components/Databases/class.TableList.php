<?php
	/**
	* class.TableList.php
	* Copyright 2009-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* This class is a PHP representation of table data over multiple rows, using this would allow the programmer to not work with the SQL interface directly
	*/
	class TableList implements \Iterator, \ArrayAccess {
		/**
		 * The total amount of rows available
		 * @var int
		 */
		private $count = false;

		/**
		* Paramters passed to the constructor
		* @var Params
		*/
		private $params;

		/**
		 * Current position of the iterator
		 * @var int
		 */
		private $position;

		/**
		 * The query for looping results
		 * @var \PDOStatement
		 */
		private $query;

		/**
		 * @var array List of rows and columns fetched from the database
		 */
		private $result;

		/**
		* The total amount of rows available (not all might be fetched)
		* @var int
		*/
		private $rows;

		/**
		 * Load a list of data table
		 * @param \forge\components\Databases\Params|array $params Parameters
		 * @return void
		 * @throws \Exception
		 * @throws Exception
		 */
		public function __construct($params) {
			// Save the arguments
			if (is_array($params))
				$this->params = new Params($params);
			elseif (get_class($params) == 'forge\\components\\Databases\\Params')
				$this->params = $params;
			else
				throw new Exception('Invalid parameters');

			// Perform the query
			$this->query();
		}

		/**
		* Browse to a specific page in the result set
		* @param int Page number
		* @return void
		*/
		public function browse($page) {
			$this->arguments[4] = $page;
			call_user_func_array(array($this,'query'),$this->arguments);
		}

		/**
		* Get the arguments this object was called with
		* @return array
		*/
		public function getArguments() {
			return $this->arguments;
		}

		/**
		 * Make sure that we have counted the amount of rows available
		 * @return void
		 */
		private function count() {
			if ($this->count === false) {
				$query = $this->params->engine->buildCount($this->params);
				$this->params->engine->bindWhere($query, $this->params);
				$query->execute();

				$this->count = $query->fetch(\PDO::FETCH_COLUMN);
			}
		}

		/**
		* Get the results in an array
		* @return array
		*/
		public function getArray() {
			return (array)$this->result;
		}

		/**
		* Get the columns
		* @return array
		*/
		public function getColumns() {
			return $this->arguments[0]->getColumnNames();
		}

		/**
		* Get the current page number
		* @return int
		*/
		public function getPage() {
			return (int)$this->params->page;
		}

		/**
		* Get the total amount of pages available
		* @return int
		*/
		public function getPages() {
			self::count();

			return $this->params->limit > 0 ? ceil($this->count/$this->params->limit) : 1;
		}

		/**
		* Get the total amount of rows available (not all might be selected)
		* @return int
		*/
		public function getRows() {
			return (int)$this->rows;
		}

		/**
		* Go back to first element
		* @return void
		*/
		public function rewind() {
			$this->position = 0;
		}

		/**
		 * Fetch current element off array list
		 * @return Table
		 */
		public function current() {
			return $this->result[$this->position];
		}

		/**
		* Return the current key
		* @return int
		*/
		public function key() {
			return $this->position;
		}

		/**
		* Move one element further down the result set
		*/
		public function next() {
			++$this->position;
		}

		/**
		 * Does this offset exist?
		 * @param $offset
		 * @return bool
		 */
		public function offsetExists($offset) {
			return isset($this->result[$offset]);
		}

		/**
		 * Get an offset
		 * @param $offset
		 * @return mixed
		 */
		public function offsetGet($offset) {
			return $this->result[$offset];
		}

		/**
		 * Set an offset
		 * @param $offset
		 * @param $value
		 */
		public function offsetSet($offset, $value) {
			/* void */
		}

		/**
		 * Unset an offset
		 * @param $offset
		 */
		public function offsetUnset($offset) {
			/* void */
		}

		/**
		* Check if the current position exists
		* @return bool
		*/
		public function valid() {
			return $this->position < $this->rows;
		}

		/**
		* Length
		* @return int
		*/
		public function length() {
			return $this->query->rowCount();
		}

		/**
		* Query the database and get all rows
		* @return void
		*/
		private function query() {
			// Build the query
			$this->query = $this->params->engine->buildSelect($this->params);

			// Set the parameters
			$this->params->engine->bindWhere($this->query, $this->params);

			// Run the query & fetch the results
			$this->query->execute();
			$this->position = 0;
			$this->result = $this->query->fetchAll(\PDO::FETCH_ASSOC);
			$this->rows = count($this->result);

			// Instantiate the results
			$class = get_class($this->params->type);
			foreach ($this->result as &$result) {
				$instance = new $class;

				foreach ($result as $column => $value)
					$instance->$column = $value;

				$result = $instance;
			}
		}
	}
