<?php
	/**
	* class.TableList.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* This class is a PHP representation of table data over multiple rows, using this would allow the programmer to not work with the SQL interface directly
	*/
	class TableList implements \Iterator {
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
		* List of results as objects
		* @var array
		*/
		private $result;

		/**
		* The total amount of rows available (not all might be fetched)
		* @var int
		*/
		private $rows;
		
		/**
		* Data type to handle
		* @var Table
		*/
		private $type;

		/**
		* Load a list of data table
		* @param \forge\components\Databases\Params|array $params Parameters
		* @return void
		* @throws Exception
		*/
		public function __construct($params) {
			// Save the arguments
			if (is_array($params))
				$this->params = new Params($params);
			elseif (get_class($params) == 'forge\\components\\Databases\\Params')
				$this->params = $params;
			else
				throw new \Exception(_('Invalid parameters'));

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
			reset($this->result);
		}

		/**
		* Fetch current element off array list
		* @return DataObject
		*/
		public function current() {
			return current($this->result);
		}

		/**
		* ?
		* @return ?
		*/
		public function key() {
			return key($this->result);
		}

		/**
		* Move one element further and return it
		* @return DataObject
		*/
		public function next() {
			return next($this->result);
		}

		/**
		* ?
		* @return ?
		*/
		public function valid() {
			return $this->current() !== false;
		}

		/**
		* Length
		* @return int
		*/
		public function length() {
			return count($this->result);
		}

		/**
		* Query the database and get all rows
		* @return void
		*/
		private function query() {
			// Reset results
			$this->result = array();

			// Prepare the where columns
			$values = array();
			if (!$this->params->type->isGlobal() && !$this->params->global && !isset($this->params->where['forge_website']))
				$this->params->where['forge_website'] = \forge\components\Websites::getId();

			// Build the query
			$query = $this->params->engine->buildSelect($this->params);

			// Set the parameters
			$this->params->engine->bindWhere($query, $this->params);
			
			// Run the query & fetch the results
			$query->execute();
			$this->result = $query->fetchAll(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, get_class($this->params->type));
		}
	}