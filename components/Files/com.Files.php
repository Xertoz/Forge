<?php
    /**
    * com.Files.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components;
    use \forge\Component;

    /**
    * File manager
    */
    class Files extends Component implements \forge\components\Dashboard\InfoBox {
        /**
        * Permissions
        * @var array
        */
        static protected $permissions = array(
            'Files' => array(
                'admin' => array(
                    'use'
                )
            )
        );

        /**
         * Get the infobox for the dashboard as HTML source code
         * @return string
         */
        static public function getInfoBox() {
            if (!\forge\components\Accounts::getPermission(\forge\components\Accounts::getUserId(),'files','admin','use','r'))
                return null;

            $free = 0;

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('files')) as $file)
                $free += $file->getSize();

            $free = \forge\String::bytesize($free);

            return \forge\components\Templates::display('components/Files/tpl/inc.infobox.php',array('free'=>$free));
        }

        /**
        * Copy a file to a new location
        * @param string Source file path
        * @param string Target file path
        * @return void
        */
        static public function copy($from,$to) {
            try {
                // Secure the paths
                self::securePath($from);
                self::securePath($to);
                
                // Make sure the target file exists
                self::create($to);
                
                // Open up the input & output streams
                $input = fopen($from,'r');
                $output = fopen($to,'w');
                
                // Transfer all content from the input to the output
                while (!feof($input))
                    fwrite($output,fread($input,1024));
                
                // Close the streams
                fclose($input);
                fclose($output);
            }
            catch (\Exception $e) {
                // If we encountered an error, just remove the output file...
                self::remove($to);
                
                // ... and continue passing down the exception
                throw $e;
            }
        }
        
        /**
        * Create a new file
        * @param string File path
        * @return void
        */
        static public function create($Path) {
            $Dir = explode('/',$Path);
            array_pop($Dir);
            $Dir = implode('/',$Dir);
            if (strlen($Dir) > 0 && !is_dir($Dir))
                if (!mkdir($Dir,0777,true))
                    throw new \Exception('Couldn\'t create file '.$Path);
            $h = fopen($Path,'w');
            fclose($h);
            chmod($Path,0777);
        }

        /**
        * @desc Remove a file or a directory including files
        * @param string File or directory to remove
        * @return void
        */
        static public function remove($File) {
            // It has to exist
            if (!file_exists($File))
                throw new \Exception('File does not exist ('.$File.')');

            // If it's a file, just remove it.
            if (!is_dir($File)) {
                if (!@unlink($File))
                    throw new \Exception('Could not remove file ('.$File.')');
            }
            // If it's a directory, iterate through it and remove its contents
            else {
                foreach (glob($File.'/*') as $SubFile) {
                    self::remove($SubFile);
                }
                rmdir($File);
            }
        }

        /**
        * Secure a file name
        * @param string File name
        */
        static function secureName(&$Name) {
            $Path = str_replace(array('/','\\'),'',$Name);
        }

        /**
        * Secure a file path
        * @param string Path
        */
        static public function securePath(&$Path) {
            $Path = str_replace('..','.',$Path);
        }
    }
