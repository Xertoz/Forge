<?php
    /**
    * engine.SQLite.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Databases\drivers;

    /**
    * Provide driver support for SQLite databases
    */
    class SQLite extends \forge\components\Databases\drivers\MySQL {
        /**
        * Initiate the class and connect to the requested database
        * @param SqlParams Parameters as loaded from configuration
        * @return void
        * @throws HttpException
        */
        public function __construct(\forge\components\Databases\Params $params) {
            try {
                $this->pdo = new \PDO('sqlite:'.$params->hostname);
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
            catch (\Exception $e) {
                throw new \forge\HttpException('Failed to open the database file',\forge\HttpException::HTTP_SERVICE_UNAVAILABLE);
            }
        }

        /**
        * Get a class name for a given type
        * @param SqlParams Parameters
        * @return string
        * @throws Exception
        */
        public function getType(\forge\components\Databases\Params $params) {
            $class = 'forge\\components\\Databases\\drivers\\MySQL\\'.$params->type;

            if (!class_exists($class))
                throw new \Exception('Type '.$params->type.' was not found');

            return $class;
        }
    }
?>