<?php
    /**
    * page.FileRequest.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Files;

    /**
    * The view that accounts for file requests.
    */
    class FileRequest {
        /**
        * File path
        * @var string
        */
        protected $path = '';

        /**
        * Buffer length while reading files
        * @var int
        */
        const BUFFER_LENGTH = 1000000;

        /**
        * Initialize the request
        */
        public function __construct($options) {
            $this->path = 'files/'.$options['url'];
        }

        /**
        * Normalize the view
        * @return string
        */
        public function __toString() {
            if (!file_exists($this->path))
                throw new \forge\HttpException('File was not found.',\forge\HttpException::HTTP_NOT_FOUND);

            $finfo = finfo_open(FILEINFO_SYMLINK | FILEINFO_MIME_TYPE | FILEINFO_MIME_ENCODING);
            header('Content-type: '.finfo_file($finfo,$this->path));
            header('Content-length: '.filesize($this->path));

            if (($fh = fopen($this->path,'rb')) !== false) {
                fpassthru($fh);
                fclose($fh);
            }
            else
                throw new \forge\HttpException('Could not open file',\forge\HttpException::HTTP_FORBIDDEN);

            return (string)null;
        }
    }