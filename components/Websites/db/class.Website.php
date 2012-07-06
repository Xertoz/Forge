<?php
    /**
    * tbl.websites.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Websites\db;

    /**
    * Data model for information regarding domains the system serves
    */
    class Website extends \forge\components\Databases\Table {
        /**
        * Table name
        * @var string
        */
        static protected $table = 'websites';

        /**
        * We are global
        * @var bool
        */
        static protected $global = true;

        /**
        * Website domain
        * @var string
        */
        public $domain = 'TinyText';

        /**
        * Website alias of?
        * @var string
        */
        public $alias = 'TinyText';
    }