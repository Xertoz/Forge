<?php
	/**
	* class.Params.php
	* Copyright 2011-2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* A class for holding parameters to method calls
	*/
	class Params {
		/**
		* The engine to use
		* @var \forge\components\Databases\Engine
		*/
		public $engine;

		/**
		 * Limit the amount of results?
		 * @var int
		 */
		public $limit = false;
		
		/**
		 * Order by specific columns?
		 * @var array
		 */
		public $order = [];
		
		/**
		 * Get which page of the result set?
		 * @var int
		 */
		public $page = 1;

		/**
		* The object type
		* @var \forge\components\Databases\Table
		*/
		public $type = null;
		
		/**
		* The WHERE map
		* @var array
		*/
		public $where = array();

		/**
		* Construct the parameter object
		* @param array Initial parameters
		* @return void
		*/
		public function __construct($params=array()) {
			// Loop over set parameters and assign them
			foreach ($params as $key => $value)
				$this->$key = $value;

			// We could get a table name out of the type
			if (!is_null($this->type))
				$this->table = $this->type->getTable();

			// Set a specific engine?
			if (is_null($this->engine))
				$this->engine = \forge\components\Databases::getEngine();

			// Fetch items not yet trashed per default, if the Trashable trait is used
			if (!is_null($this->type) && in_array('forge\\components\\Databases\\Trashable', class_uses($this->type))
				&& !isset($this->where['trashed']))
				$this->where['trashed'] = false;
		}

		/**
		* Return null if a parameter was not set
		* @param string Parameter name
		* @return null
		*/
		public function __get($member) {
			return null;
		}
		
		static public function getColumnOperator($column) {
			$array = explode(':', $column, 2);
			return count($array) < 2 ? ['is', $column] : $array;
		}
	}