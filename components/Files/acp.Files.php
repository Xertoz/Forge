<?php
    /**
    * acp.Files.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Files;
    use \forge\components\Files;
    use \forge\components\Accounts;
    use \forge\HttpException;

    /**
    * File component for Forge 4
    * Administration interface
    */
    class adminInterface {
        static public function index() {
            Accounts::restrict('Files','admin','use','r');

            $path = 'files/'.(empty($_REQUEST['path']) ? null : $_REQUEST['path']).'*';
            \forge\components\Files::securePath($path);
            $files = array();
            foreach (glob($path) as $file)
                $files[] = array(
                    'date' => date('Y-m-d H:i:s',filectime($file)),
                    'name' => substr($file,strlen($path)-1),
                    'size' => filesize($file),
                    'type' => filetype($file)
                );
            $matrix = new \forge\components\XML\ArrayMatrix($files,array('name','dir'));

            return \forge\components\Templates::display(
                'components/Files/tpl/acp.files.php',
                array(
                    'matrix' => $matrix
                )
            );
        }
    }