<?php
	/**
	* class:Table.php
	* Copyright 2011-2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* Database abstraction layer for tables
	*/
	abstract class Table implements \ArrayAccess {
		/**
		 * Has the instance been altered with? (and differs from the source data?)
		 */
		protected $__changed = false;
		
		/**
		* The columns of this model
		*/
		protected $__columns;

		/**
		* The underlying SQL engine
		* @var Engine
		*/
		protected $__engine;

		/**
		* Should Forge handle this integrity in its default engine?
		* @var bool
		*/
		static protected $handleIntegrity = true;

		/**
		* The table name of this model
		* @var string
		*/
		static protected $table = null;

		/**
		* The ID column of the row
		* @var string
		*/
		static protected $__id = 'forge_id';

		/**
		 * Add a column to this table model
		 * @param $column
		 * @param $properties
		 * @throws \Exception
		 */
		final public function addColumn($column, $properties) {
			if (isset($this->__columns[$column]))
				throw new \Exception('Double declaration of model column '.get_class($this).'->'.$column);

			// Instantiate the object type
			try {
				if (!$properties instanceof Params) {
					$params = new Params();
					$params->table = $this;

					if (is_string($properties))
						$params->type = $properties;
					elseif (is_array($properties)) {
						$params->type = $properties[0];
						unset($properties[0]);

						foreach ($properties as $key => $value)
							$params->$key = $value;
					}
					else
						throw new \Exception('Invalid argument');
				}
				else
					$params = $properties;
				
				$params->column = $column;

				$type = $this->__engine->getType($params);
				$this->__columns[$column] = new $type($params);
			}
			catch (\Exception $e) {
				throw new \Exception('Could not instantiate the model column '.get_class($this).'->'.$column);
			}
		}

		/**
		 * Initiate the instance
		 * @param bool $id
		 * @param bool $engine
		 * @throws exceptions\NoData
		 */
		public function __construct($id=false, $engine=false) {
			// Retrieve the engine we will use for queries
			$this->__engine = $engine !== false ? $engine : \forge\components\Databases::getEngine();

			// Make sure that we have an ID column
			if (static::$__id == 'forge_id') {
				$idType = $this->__engine->getNamespace().'\Integer';
				$params = new Params();
				$params->default = null;
				$params->null = false;
				$params->primary = true;
				$params->increment = true;
				$this->__columns['forge_id'] = new $idType($params);
			}

			// Loop over the members of this class for declared columns
			foreach ($this as $column => $params)
				if (substr($column, 0, 2) != '__') {
					// Unset the public member, so we will overload with __get and __set
					unset($this->$column);
					$this->addColumn($column, $params);
				}

			// Loop over any additionally declared columns
			foreach (\forge\components\Databases::getColumns(get_class($this)) as $column => $params)
				$this->addColumn($column, $params);

			// Was a specific ID requested?
			if ($id !== false && !is_null($id)) {
				$this->__columns[static::$__id]->set($id);
				$this->select(static::$__id);
			}
		}

		/**
		 * Get the column value?
		 * @param string Column name
		 * @return mixed
		 * @throws \Exception
		 */
		final public function __get($member) {
			if (!isset($this->__columns[$member]))
				throw new \Exception('A reference to an undeclared column was made');

			return $this->__columns[$member]->get();
		}

		/**
		 * Set a new column value?
		 * @param $member
		 * @param $value
		 * @return void
		 * @throws \Exception
		 */
		final public function __set($member,$value) {
			if (!isset($this->__columns[$member]))
				throw new \Exception('A reference to an undeclared column was made');

			$this->__changed = $this->__changed || $this->__columns[$member]->get() !== $value;
			$this->__columns[$member]->set($value);
		}

		/**
		* Executed method before any INSERT query
		* @return void
		*/
		protected function beforeInsert() {}

		/**
		* Executed method before any UPDATE query
		* @return void
		*/
		protected function beforeSave() {}

		/**
		* Build a CREATE statement for this
		* @return string
		*/
		final public function buildCreate() {
			return $this->__engine->buildCreate($this);
		}

		/**
		* Check the database integrity for this model
		* @return bool
		*/
		final public function checkIntegrity() {
			return $this->__engine->checkIntegrity($this);
		}

		/**
		 * Insert a new row into the database
		 * @return void
		 * @throws Exception
		 * @throws \Exception
		 */
		public function delete() {
			$params = new Params();

			$params->table = static::$table;
			$params->where = array(static::$__id);

			$query = $this->__engine->buildDelete($params);

			$query->bindValue(
				1,
				$this->__columns[static::$__id]->get(),
				$this->__columns[static::$__id]->getDataType()
			);

			$query->execute();
		}

		/**
		* Fix this model's data integrity
		* @return void
		*/
		final public function fixIntegrity() {
			$this->__engine->fixIntegrity($this);
		}

		/**
		* Get all available columns
		* @param bool Get the entire source?
		* @return array
		*/
		final public function getColumns($everything=false) {
			return $everything ? $this->__columns : array_keys($this->__columns);
		}

		/**
		* Get the current CREATE statement for this
		* @return string
		*/
		final public function getCreate() {
			return $this->__engine->getCreate($this);
		}
		
		/**
		 * Return all models we depend upon
		 * @return array
		 */
		final public function getDependencies() {
			$dependencies = [];
			
			foreach ($this->__columns as $column)
				foreach ($column->getDependencies() as $dependency)
					$dependencies[] = $dependency;
			
			return array_unique($dependencies);
		}

		/**
		* Get the row ID
		* @return mixed
		*/
		final public function getId() {
			return $this->__columns[static::$__id]->get();
		}

		/**
		* Get the name of the ID column
		* @return string
		*/
		final static public function getIdColumn() {
			return static::$__id;
		}

		/**
		* Get the table name
		* @return string
		*/
		final static public function getTable() {
			return static::$table;
		}

		/**
		* Get a column object
		* @param string Column
		* @return \forge\components\Databases\Type
		*/
		final public function getType($column) {
			return $this->__columns[$column];
		}

		/**
		* Get the table prefix
		* @return string
		*/
		final public function getPrefix() {
			return $this->__engine->getPrefix();
		}

		/**
		* Should Forge handle the data integrity?
		* @return bool
		*/
		final public function handleIntegrity() {
			return (bool)static::$handleIntegrity;
		}

		/**
		 * Insert a new row into the database
		 * @return void
		 * @throws Exception
		 * @throws \Exception
		 */
		final public function insert() {
			$this->beforeInsert();

			$params = new Params();

			$params->table = static::$table;
			$params->columns = array();
			foreach ($this->__columns as $column => $type)
				if (($column != static::$__id || !$this->__columns[$column]->getIncrement()) && !$this->__columns[$column]->isVirtual())
					$params->columns[] = $column;

			list($query, $columns) = $this->__engine->buildInsert($this, $params);

			for ($i=0;$i<count($columns);$i++) {
				$value = $this->__columns[$columns[$i]]->get();
				if (is_object($value)) {
					if ($value instanceof Table) {
						$value = $value->getId();
						
						if ($value === 0)
							$value = null;
					}
				}
				
				$query->bindValue(
					$i+1,
					$value,
					$this->__columns[$columns[$i]]->getDataType()
				);
			}

			$query->execute();

			$this->__changed = false;
			if ($this->__columns[static::$__id]->getIncrement())
				$this->__columns[static::$__id]->set($this->__engine->getPDO()->lastInsertId());
		}
		
		/**
		 * Should this model's integrity be handled?
		 * @return bool
		 */
		final static public function isHandled() {
			return static::$handleIntegrity;
		}

		/**
		 * Does this offset exist?
		 * @param int $offset
		 * @return bool
		 */
		public function offsetExists($offset) {
			return array_key_exists($offset, $this->__columns);
		}

		/**
		 * Get an offset
		 * @param int $offset
		 * @return Column
		 * @throws \Exception
		 */
		public function offsetGet($offset) {
			return $this->__get($offset);
		}

		/**
		 * Set an offset
		 * @param int $offset
		 * @param mixed $value
		 * @throws \Exception
		 */
		public function offsetSet($offset, $value) {
			$this->__set($offset, $value);
		}
		
		/**
		 * Unset an offset
		 * @param int $offset
		 */
		public function offsetUnset($offset) {
			/* void */
		}

		/**
		 * Save row changes to the database
		 * @param bool Force update even if there are no changes
		 * @return bool
		 * @throws \Exception
		 */
		final public function save($force=false) {
			if (!$force && !$this->__changed)
				return;
			
			$this->beforeSave();

			$params = new Params();

			$params->columns = array_keys($this->__columns);
			$params->columns = [];
			foreach ($this->__columns as $column => $object)
				if (!$object->isVirtual())
					$params->columns[] = $column;
			$params->table = static::$table;
			$params->where = array(static::$__id);
			$query = $this->__engine->buildUpdate($params);

			$i = 1;
			foreach ($this->__columns as $column => $type) {
				if ($type->isVirtual())
					continue;
				
				$value = $type->get();
				if (is_object($value)) {
					if ($value instanceof Table) {
						$value = $value->getId();
						
						if ($value === 0)
							$value = null;
					}
				}
				
				$query->bindValue($i++,$value,$type->getDataType());
			}
			$query->bindValue(
				$i,
				$this->__columns[static::$__id]->get(),
				$this->__columns[static::$__id]->getDataType()
			);

			$query->execute();
			
			return $query->rowCount() == 1;
		}

		/**
		 * Select a row from the table by the given constraints
		 * @param array Columns to bind
		 * @return Table
		 * @throws exceptions\NoData
		 */
		public function select($columns) {
			$columns = !is_array($columns) ? array($columns) : $columns;
			
			$params = new Params();
			$params->type = $this;
			foreach ($columns as $column) {
				$value = $this->__columns[$column]->get();
				
				if ($value instanceof Table)
					$value = $value->getId();
				
				$params->where[$column] = $value;
			}
			$params->table = static::$table;

			$query = $this->__engine->buildSelect($params);
			$this->__engine->bindWhere($query, $params);
			$query->execute();
			$result = $query->fetch(\PDO::FETCH_ASSOC);
			if ($result === false)
				throw new exceptions\NoData;

			foreach ($result as $column => $value)
				$this->__columns[$column]->set($value);
			
			$this->__changed = false;

			return $this;
		}

		/**
		 * Write a row to the table by either insertion or updating
		 * @param bool Force update even if there are no changes
		 * @return void
		 * @throws Exception
		 * @throws \Exception
		 */
		final public function write($force=false) {
			if ($this->getId() == 0)
				$this->insert();
			else
				$this->save($force);
		}
	}