<?php
    /**
    * ajax.Files.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Files;
    use \forge\components\Accounts;

    /**
    * Component File component's AJAX callback
    */
    class Ajax extends \forge\components\XML\controllers\XML {
        /**
        * Return complete directory tree
        * @param XMLWriter
        * @return void
        */
        static public function directories($xml) {
            Accounts::restrict('Files','admin','use','r');

            $xml->startElement('filesystem');
            $xml->writeAttribute('path','/files/');

            function glob_r($path,$xml) {
                foreach (glob($path.'/*',GLOB_ONLYDIR) as $directory) {
                    $xml->startElement('directory');
                    $xml->writeAttribute('path','/'.$directory);
                    glob_r($directory,$xml);
                    $xml->endElement();
                }
            }

            glob_r('files',$xml);

            $xml->endElement();
        }

        /**
        * Return complete file list within directory
        * @param XMLWriter
        * @return void
        */
        static public function browse($xml) {
            Accounts::restrict('Files','admin','use','r');

            // Make sure we do have path
            if (!isset($_REQUEST['path']) || !is_string($_REQUEST['path']))
                throw new \forge\HttpException('Parameter path (string) needs to be properly set',\forge\HttpException::HTTP_BAD_REQUEST);

            // If it doesn't start with /files it's not allowed
            \forge\components\Files::securePath($_REQUEST['path']);
            if (substr($_REQUEST['path'],0,strlen('/files')) != '/files')
                throw new \forge\HttpException('The requested path is not allowed',\forge\HttpException::HTTP_FORBIDDEN);

            // Write files into XML
            $xml->startElement('directory');
            $xml->writeAttribute('path',$_REQUEST['path']);
            foreach (glob('.'.$_REQUEST['path'].'/*') as $file) {
                //if (is_dir($file))
                    //continue;

                $filename = substr($file,strlen($_REQUEST['path'])+2);
                $xml->startElement('file');
                $xml->writeAttribute('name',utf8_encode($filename));
                $xml->writeAttribute('size',filesize($file));
                $xml->writeAttribute('date',date('Y-m-d H:i:s',filectime($file)));
                $xml->writeAttribute('type',filetype($file));
                $xml->endElement();
            }
            $xml->endElement();
        }

        /**
        * Create a new directory
        * @param XMLWriter
        * @return void
        */
        static public function createDirectory(\XMLWriter $xml) {
            Accounts::restrict('Files','admin','use','w');

            // Make sure we do have path
            if (!isset($_REQUEST['path']) || !is_string($_REQUEST['path']))
                throw new \forge\HttpException('Parameter path (string) needs to be properly set',\forge\HttpException::HTTP_BAD_REQUEST);

            // If it doesn't start with /files it's not allowed
            \forge\components\Files::securePath($_REQUEST['path']);
            if (substr($_REQUEST['path'],0,strlen('/files/')) != '/files/' || strlen($_REQUEST['path']) <= strlen('/files'))
                throw new \forge\HttpException('The requested path is not allowed',\forge\HttpException::HTTP_FORBIDDEN);

            // Create the directory!
            if (!file_exists($_REQUEST['path']))
                mkdir(getcwd().$_REQUEST['path'],0777,true);

            // Success.
            $xml->writeElement('directory');
        }

        /**
        * Delete a file or directory
        * @param XMLWriter
        * @return void
        */
        static public function delete(\XMLWriter $xml) {
            Accounts::restrict('Files','admin','use','w');

            // Make sure we do have path
            if (!isset($_REQUEST['path']) || !is_string($_REQUEST['path']))
                throw new \forge\HttpException('Parameter path (string) needs to be properly set',\forge\HttpException::HTTP_BAD_REQUEST);

            // If it doesn't start with /files it's not allowed
            \forge\components\Files::securePath($_REQUEST['path']);
            if (substr($_REQUEST['path'],0,strlen('/files/')) != '/files/' || strlen($_REQUEST['path']) <= strlen('/files'))
                throw new \forge\HttpException('The requested path is not allowed',\forge\HttpException::HTTP_FORBIDDEN);

            // Delete the directory!
            try {
                \forge\components\Files::remove(getcwd().$_REQUEST['path']);
            }
            catch (\Exception $e) {
                throw new \forge\HttpException('Could not remove file ('.$_REQUEST['path'].')',\forge\HttpException::HTTP_BAD_REQUEST);
            }

            // Success.
            $xml->writeElement('delete');
        }

        /**
        * Get file information
        */
        static public function info(\XMLWriter $xml) {
            \forge\components\Files::securePath($file = substr($_GET['file'],1));

            if (!file_exists($file))
                throw new \forge\HttpException('File not found',\forge\HttpException::HTTP_NOT_FOUND);

            $xml->startElement('file');
            $xml->writeElement('name',$file);
            $xml->startElement('size');
            $xml->writeAttribute('unit','b');
            $xml->writeRaw(filesize($file));
            $xml->endElement();
            $xml->writeElement('changed',date('Y-m-d H:i:s',filectime($file)));
            $xml->endElement();
        }

        /**
        * Upload a file
        * @param XMLWriter
        * @return void
        */
        static public function upload($xml) {
            Accounts::restrict('Files','admin','use','w');

            // Make sure we do have path
            if (!isset($_REQUEST['path']) || !is_string($_REQUEST['path']))
                throw new \forge\HttpException('Parameter path (string) needs to be properly set',\forge\HttpException::HTTP_BAD_REQUEST);

            // If it doesn't start with /files it's not allowed
            \forge\components\Files::securePath($_REQUEST['path']);
            if (substr($_REQUEST['path'],0,strlen('/files')) != '/files')
                throw new \forge\HttpException('The requested path is not allowed',\forge\HttpException::HTTP_FORBIDDEN);

            // Upload it
            move_uploaded_file($_FILES['file']['tmp_name'],getcwd().$_REQUEST['path'].'/'.$_FILES['file']['name']);

            $xml->startElement('file');
            $xml->writeAttribute('name',$_FILES['file']['name']);
            $xml->writeAttribute('size',filesize(getcwd().$_REQUEST['path'].'/'.$_FILES['file']['name']));
            $xml->writeAttribute('date',date('Y-m-d H:i:s',filectime(getcwd().$_REQUEST['path'].'/'.$_FILES['file']['name'])));
            $xml->writeAttribute('type',filetype(getcwd().$_REQUEST['path'].'/'.$_FILES['file']['name']));
            $xml->endElement();
        }
    }