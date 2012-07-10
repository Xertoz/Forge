<?php
	/**
	* class.Type.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* Root class for model column types
	*/
	abstract class Type {
		/**
		* Default value
		* @var string
		*/
		protected $default = null;

		/**
		* Use AUTO_INCREMENT?
		* @var bool
		*/
		protected $increment = false;

		/**
		* Use this as a one dimensional INDEX?
		* @var bool
		*/
		protected $index = false;

		/**
		* Length of this column
		* @var int
		*/
		protected $length = 0;

		/**
		* Allow NULL values?
		*/
		protected $null = null;

		/**
		* Use PRIMARY KEY?
		* @var bool
		*/
		protected $primary = false;

		/**
		* SQL type
		* @var string
		*/
		protected $type = null;

		/**
		* Does this column implement UNIQUE?
		* @var bool
		*/
		protected $unique = false;

		/**
		* The column value
		*/
		protected $value = null;

		/**
		* Construct an instance
		* @return void
		*/
		public function __construct(Params $params=null) {
			if (!is_null($params))
				foreach ($params as $key => $value)
					if ($key != 'type')
						$this->$key = $value;
		}

		/**
		* Build the SQL for creating this column
		* @param string Column name
		* @return string
		*/
		public function buildCreate($column) {
			$sql = ['`'.$column.'`',$this->type.($this->length ? '('.$this->length.')' : null)];

			if ($this->null !== null && !$this->null)
				$sql[] = 'NOT NULL';

			if ($this->default !== null)
				$sql[] = 'DEFAULT '.$this->default;

			if ($this->increment)
				$sql[] = 'AUTO_INCREMENT';

			$sql = implode(' ',$sql);

			return $sql;
		}

		/**
		* Build the indexes part for a CREATE statement
		*/
		public function buildIndexes($column) {
			$indexes = array();

			if ($this->primary)
				$indexes[] = 'PRIMARY KEY (`'.$column.'`)';

			if ($this->index)
				$indexes[] = 'KEY `'.$column.'` (`'.$column.'`)';

			if ($this->unique)
				$indexes[] = 'UNIQUE KEY `'.$column.'` (`'.$column.'`)';

			return $indexes;
		}

		/**
		* Get the current value
		* @return mixed
		*/
		public function get() {
			return $this->value;
		}

		/**
		* Get the PDO data type of this column
		* @return int
		*/
		public function getDataType() {
			return \PDO::PARAM_STR;
		}

		/**
		* Set a new value
		* @param mixed New value
		* @return void
		* @throws Exception
		*/
		public function set($value) {
			$this->value = $value;
		}
	}