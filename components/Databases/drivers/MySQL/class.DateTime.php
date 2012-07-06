<?php
    /**
    * mysql.DateTime.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Databases\drivers\MySQL;

    /**
    * MySQL data type DateTime
    */
    class DateTime extends \forge\components\Databases\Type {
    	/**
    	* Default value
    	*/
    	protected $default = 'NULL';

    	/**
    	* Is NULL type
    	*/
    	protected $null = true;

        /**
        * SQL type
        * @var string
        */
        protected $type = 'datetime';

        /**
        * Set a new value
        * @param mixed New value
        * @return void
        * @throws Exception
        */
        public function set($value) {
            $this->value = is_numeric($value) ? date('Y-m-d H:i:s', $value) : $value;
        }
    }