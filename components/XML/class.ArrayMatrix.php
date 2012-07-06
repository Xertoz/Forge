<?php
    /**
    * class.ArrayMatrix.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\XML;

    /**
    * Create a graphical matrix out of an associative array
    */
    class ArrayMatrix extends Matrix {
        /**
        * Initiate this matrix
        * @param array Associative array
        * @param array Column names
        * @return void
        */
        public function __construct($array,$columns) {
            $this->columns = $columns;
            $this->rows = $array;
        }

        /**
        * Get the column data
        * @return array
        */
        public function getColumns() {
            return $this->columns;
        }

        /**
        * Get the current page number
        * @return int
        */
        public function getPage() {
            return 1;
        }

        /**
        * Get the total available pages
        * @return int
        */
        public function getPages() {
            return 1;
        }

        /**
        * Get the total amount of rows available
        * @return int
        */
        public function getRows() {
            return $this->rows;
        }
    }