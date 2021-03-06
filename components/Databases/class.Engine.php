<?php
	/**
	* class.Engine.php
	* Copyright 2011-2014 Mattias Lindholm
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
		* @param Params $params Parameters as loaded from configuration
		* @return void
		*/
		abstract public function __construct(Params $params);
		
		/**
		 * Bind values to the WHERE clause of a query
		 * @param \PDOStatement $query The query to bind to
		 * @param Params $params The parameters to run off of
		 * @return void
		 */
		public function bindWhere(\PDOStatement $query, Params $params) {
			$bind = function(\PDOStatement $query, $where, &$n=1) use (&$bind, $params) {
				foreach ($where as $column => $value) {
					if ($value instanceof Table)
						$value = $value->getId();
					
					list($operator, $column) = $params->getColumnOperator($column);
					
					switch ($operator) {
						default:
							if (is_array($value))
								$bind($query, $value, $n);
							else
								$query->bindValue(
									$n++,
									$value,
									$params->type->getType(
											$column
									)->getDataType()
								);
							break;
						
						case 'in':
							foreach ($value as $item)
								$query->bindValue(
									$n++,
									$item,
									$params->type->getType(
											$column
									)->getDataType()
								);
							break;
					}
				}
			};
			
			$bind($query, $params->where);
		}

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
		* @param Params $params Parameters
		* @return \PDOStatement
		*/
		abstract public function buildDelete(Params $params);

		/**
		* Prepare a query for insertion
		* @param Table $table Table
		* @param Params $params Parameters
		* @return array
		*/
		abstract public function buildInsert(Table $table, Params $params);
		
		final public function buildOrder(Params $params) {
			$order = 'ORDER BY';
			
			$args = [];
			foreach ($params->order as $column => $type)
				$args[] = '`'.$column.'` '.$type;
			$order .= ' '.implode(', ', $args);
			
			return count($params->order) ? $order : null;
		}

		/**
		* Prepare a query for selection
		* @param Params $params Parameters
		* @return \PDOStatement
		*/
		abstract public function buildSelect(Params $params);

		/**
		* Prepare a query for updating
		* @param Params $params Parameters
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
		* @param Params $params Parameters
		* @return string
		* @throws Exception
		*/
		abstract public function getType(Params $params);

		/**
		* Internal SQL compilation function
		* @param array $where
		* @param string|false $parent
		* @return string
		*/
		final private function makeWhere($where,$parent=false) {
			$sql = null;
			$statements = array();

			if ($parent === false || is_int($parent))
				foreach ($where as $key => $value) {
					$column = !is_int($key) ? $key : $value;
					$t = Params::getColumnOperator($column);

					if ($t[0] == 'is' && is_array($value))
						$statements[] = '('.self::makeWhere($value,$key).')';
					else {
						$operators = array(
							'is' => '=',
							'not' => '!=',
							'null' => 'IS NULL',
							'gt' => '>',
							'lt' => '<',
							'in' => 'IN'
						);

						$operator = in_array($t[0],array_keys($operators)) ? $operators[$t[0]] : $operators['is'];
						
						switch ($operator) {
							default:
								$param = '?';
								break;
							
							case 'IN':
								if (count($value))
									$param = '('.implode(', ', array_fill(0, count($value), '?')).')';
								else {
									// Make an impossible statement for null IN values
									$operator = '!=';
									$param = $t[1];
								}
								break;
							
							case 'IS NULL':
								$param = null;
								break;
						}

						$statements[] = '`'.$t[1].'` '.$operator.' '.$param;
					}
				}

			$sql = implode(' AND ',$statements);

			return $sql;
		}
	}