<?php
	/**
	* mysql.List.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases\drivers\MySQL;

	/**
	* MySQL data type for listing other tables
	*/
	class Objects extends \forge\components\Databases\Type {
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
		protected $type = 'int';

		/**
		* The column value
		*/
		protected $value = null;
		
		/**
		 * This is a virtual column
		 */
		protected $virtual = true;
		
		/**
		 * List of the available bindings
		 * @var array
		 */
		private $list = false;
		
		public function __construct(\forge\components\Databases\Params $params=null) {
			parent::__construct($params);
			
			// Figure out what to call the intermediate class
			$this->class = get_class($this->table).ucfirst($this->column);
			$class = explode('\\', $this->class);
			$class = $class[count($class)-1];
			$model = explode('.', $this->model);
			
			// If that class does not exist, simply define it
			if (!class_exists($this->class)) {
				// Figure out where to place the file
				$file = explode('\\', $this->class);
				$file[0] = 'extend';
				$file[count($file)-1] = 'class.'.$class.'.php';
				$file = implode('/', $file);
				
				// Which namespace and class do we have?
				$namespace = explode('\\', get_class($this->table));
				$parent = array_pop($namespace);
				$addon = $namespace[count($namespace)-2];
				$namespace = implode('\\', $namespace);
				
				// Generate the actual class definition
				$php = '<?php namespace '.$namespace.'; class '.$class.' ';
				$php .= 'extends \\forge\\components\\Databases\\Table { ';
				$php .= 'static protected $global = true; ';
				$php .= 'static protected $table = \''.$this->table->getTable().'_'.strtolower($this->column).'\'; ';
				$php .= 'public $parent = [\'Foreign\', \'model\' => \''.$addon.'.'.$parent.'\', \'reference\' => false]; ';
				$php .= 'public $child = [\'Foreign\', \'model\' => \''.$model[0].'.'.$model[1].'\']; ';
				$php .= '}';
				
				// Write the file
				$folders = explode('/', $file);
				array_pop($folders);
				$path = FORGE_PATH.'/';
				foreach ($folders as $folder)
					if (!file_exists($path.=$folder.'/'))
						mkdir($path);
				file_put_contents(FORGE_PATH.'/'.$file, $php);
			}
		}
		
		public function __toString() {
			return 'ObjectList';
		}
		
		/**
		 * Add a table to this list
		 * @param \forge\components\Databases\Table $table Table to add
		 * @return bool Was the table added?
		 */
		public function add(\forge\components\Databases\Table $table) {
			$this->load();
			
			foreach ($this->list as $exist)
				if ($exist->child->getId() === $table->getId())
					return false;
			
			$bind = new $this->class;
			$bind->parent = $this->table;
			$bind->child = $table;
			$bind->insert();
			
			$this->list[] = $table;
			
			return true;
		}
		
		/**
		 * Delete a table from this list
		 * @param \forge\components\Databases\Table $table Table to delete
		 * @return bool Was the table deleted?
		 */
		public function delete(\forge\components\Databases\Table $table) {
			$this->load();
			
			foreach ($this->list as $exist)
				if ($exist->child->getId() === $table->getId()) {
					$exist->delete();
					return true;
				}
			
			return false;
		}
		
		/**
		 * Get the referenced model
		 * @return \forge\components\Databases\Table
		 */
		public function get() {
			return $this;
		}

		/**
		* Get the PDO data type of this column
		* @return int
		*/
		public function getDataType() {
			return \PDO::PARAM_INT;
		}
		
		/**
		 * Does this list contain a specific item?
		 * @param \forge\components\Databases\Table $table
		 * @return bool
		 */
		public function has(\forge\components\Databases\Table $table) {
			$this->load();
			
			foreach ($this->list as $item)
				if ($item->child->getId() === $table->getId())
					return true;
			
			return false;
		}
		
		/**
		 * Load the data
		 */
		private function load() {
			if ($this->list === false) {
				$this->list = new \forge\components\Databases\TableList([
					'type' => new $this->class,
					'where' => [
						'parent' => $this->table
					]
				]);
			}
		}

		/**
		* Set a new value
		* @param mixed New value
		* @return void
		* @throws Exception
		*/
		public function set($value) {
			$this->load();
			
			$remove = [];
			$add = [];
			
			if (!is_array($value))
				$remove = $this->list;
			else {
				// Tag removals
				foreach ($this->list as $list) {
					$exist = false;

					foreach ($value as $item)
						if ($item instanceof \forge\components\Databases\Table) {
							if ($list->getId() === $item->getId())
								$exist = true;
						}
						else
							if ($list->getId() === $item)
								$exist = true;

					if (!$exist)
						$remove[] = $list;
				}
				
				// Tag additions
				foreach ($value as $item) {
					$exist = false;
					
					foreach ($this->list as $list)
						if ($item instanceof \forge\components\Databases\Table) {
							if ($list->getId() === $item->getId())
								$exist = true;
						}
						else
							if ($list->getId() === $item)
								$exist = true;
							
					if (!$exist)
						$add[] = $item;
				}
			}
			
			foreach ($remove as $todo)
				$todo->delete();
			
			foreach ($add as $todo) {
				$row = new $this->class;
				$row->parent = $this->table;
				$row->child = $todo;
				$row->insert();
			}
		}
	}