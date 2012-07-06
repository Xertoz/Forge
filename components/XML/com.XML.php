<?php
    /**
    * com.XML.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components;

    /**
    * XML component
    */
    class XML extends \forge\Component {
        /**
        * Create an XML writer
        * @return XMLWriter
        */
        static public function createWriter() {
            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->startDocument('1.0','UTF-8');
            return $xml;
        }
    }