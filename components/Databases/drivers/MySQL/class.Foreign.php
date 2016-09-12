<?php
	/**
	* mysql.Int.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases\drivers\MySQL;

	/**
	* MySQL data type for relating other tables
	*/
	class Foreign extends \forge\components\Databases\Type {
		/**
		* Class name of model
		* @var string
		*/
		private $class;
		
		/**
		* Default value
		*/
		protected $default = 'NULL';

		/**
		* Default column length
		*/
		protected $length = 11;

		/**
		* SQL type
		* @var string
		*/
		protected $type = 'Integer';

		/**
		* The column value
		*/
		protected $value = null;
		
		public function __construct(\forge\components\Databases\Params $params=null) {
			parent::__construct($params);
			
			$model = explode('.', $this->model);
			$type = \forge\Addon::existsComponent($model[0]) ? 'components' : 'modules';
			$this->class = 'forge\\'.$type.'\\'.$model[0].'\\db\\'.$model[1];
			if (!isset($params->reference) || $params->reference) {
				$object = $this->table instanceof $this->class ? $this->table : new $this->class;
				$this->foreign = '`'.$this->engine->getPrefix().$object->getTable().'` (`'.$object->getIdColumn().'`)';
				$this->dependencies[] = get_class($object);
			}
		}
		
		/**
		 * Get the referenced model
		 * @return \forge\components\Databases\Table
		 */
		public function get() {
			return $this->value !== null && $this->value !== 0 ? $this->value : new \forge\NullObject;
		}

		/**
		* Get the PDO data type of this column
		* @return int
		*/
		public function getDataType() {
			return \PDO::PARAM_INT;
		}

		/**
		* Set a new value
		* @param mixed New value
		* @return void
		* @throws Exception
		*/
		public function set($value) {
			if (is_object($value) && $value instanceof \forge\components\Databases\Table || $value == 0)
				$this->value = $value;
			else
				$this->value = new $this->class($value);
		}
	}