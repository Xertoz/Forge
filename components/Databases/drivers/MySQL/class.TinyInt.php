<?php
    /**
    * mysql.TinyInt.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Databases\drivers\MySQL;

    /**
    * MySQL data type Int
    */
    class TinyInt extends \forge\components\Databases\Type {
    	/**
    	* Default value
    	*/
    	protected $default = '\'0\'';

    	/**
    	* Default column length
    	*/
    	protected $length = 4;

    	/**
    	* Prevent NULL values
    	*/
    	protected $null = false;

        /**
        * SQL type
        * @var string
        */
        protected $type = 'tinyint';

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
            $this->value = (int)$value;
        }
    }