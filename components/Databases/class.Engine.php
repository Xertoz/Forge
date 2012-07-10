<?php
	/**
	* class.Engine.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Databases;

	/**
	* The root class for all SQL engines used
	*/
	abstract class Engine {
		/**
		* The name of the database
		* @var string
		*/
		protected $database;

		/**
		* The PDO instance this instance will center around
		* @var \PDO
		*/
		protected $pdo;

		/**
		* Prefix all table names with this
		* @var string
		*/
		protected $prefix;

		/**
		* Initiate the class and connect to the requested database
		* @param SqlParams Parameters as loaded from configuration
		* @return void
		*/
		abstract public function __construct(Params $params);

		/**
		* Build a SELECT COUNT(*) statement for a table
		* @param \forge\components\Databases\Params $params
		* @return \PDOStatement
		*/
		abstract public function buildCount(\forge\components\Databases\Params $params);

		/**
		* Build a CREATE statement for a table
		* @param Table Table
		* @return \PDOStatement
		*/
		abstract public function buildCreate(Table $table);

		/**
		* Prepare a query for deletion
		* @param SqlParams Parameters
		* @return \PDOStatement
		*/
		abstract public function buildDelete(Params $params);

		/**
		* Prepare a query for insertion
		* @param SqlParams Parameters
		* @return \PDOStatement
		*/
		abstract public function buildInsert(Params $params);

		/**
		* Prepare a query for selection
		* @param SqlParams Parameters
		* @return \PDOStatement
		*/
		abstract public function buildSelect(Params $params);

		/**
		* Prepare a query for updating
		* @param SqlParams Parameters
		* @return \PDOStatement
		*/
		abstract public function buildUpdate(Params $params);

		/**
		* Build the WHERE part of a query according to the params
		* @param Params Parameters
		* @return \PDOStatement
		* @throws Exception
		*/
		final protected function buildWhere($params) {
			return strlen($sql = self::makeWhere($params->where)) ? 'WHERE '.$sql : null;
		}

		/**
		* Check the integrity of a model
		* @param \forge\components\Databases\Table Table model
		* @return bool
		*/
		abstract public function checkIntegrity(\forge\components\Databases\Table $table);

		/**
		* Fix the integrity of a model
		* @param \forge\components\Databases\Table Table model
		* @return void
		*/
		abstract public function fixIntegrity(\forge\components\Databases\Table $table);

		/**
		* Get this driver's namespace
		* @return string
		*/
		final public function getNamespace() {
			return get_class($this);
		}

		/**
		* Get the PDO instance
		* @return \PDO
		*/
		final public function getPDO() {
			return $this->pdo;
		}

		/**
		* Get the table prefix
		* @return string
		*/
		final public function getPrefix() {
			return $this->prefix;
		}

		/**
		* Get a class name for a given type
		* @param SqlParams Parameters
		* @return string
		* @throws Exception
		*/
		abstract public function getType(Params $params);

		/**
		* Internal SQL compilation function
		* @param array
		* @param string
		* @return string
		* @todo IN operator needs implementation
		*/
		final private function makeWhere($where,$parent=false) {
			$sql = null;
			$statements = array();

			if ($parent === false || is_int($parent))
				foreach ($where as $key => $value)
					if (is_array($value))
						$statements[] = '('.self::makeWhere($value,$key).')';
					else {
						$column = !is_int($key) ? $key : $value;
						$t = explode(':',$column);
						if (count($t) == 2) {
							$param = ':'.$t[1];
							$operators = array(
								'is' => '=',
								'gt' => '>',
								'lt' => '<',
								'in' => 'IN'
							);

							$operator = in_array($t[0],array_keys($operators)) ? $operators[$t[0]] : $operators['is'];
						}
						else {
							$param = ':'.$t[0];
							$operator = '=';
						}

						$statements[] = '`'.$column.'` '.$operator.' '.$param;
					}

			$sql = implode(' AND ',$statements);

			return $sql;
		}
	}