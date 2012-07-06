<?php
    /**
    * ajax.Software.php
    * Copyright 2009-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Software;
    use \forge\components\Software;
    use \forge\components\Accounts;
    use \forge\HttpException;

    /**
    * Software ajax component
    */
    class Ajax extends \forge\components\XML\controllers\XML {
        /**
        * Unload a module to the system
        * @return void
        */
        static public function disableModule(\XMLWriter $xml) {
            if (empty($_POST['module']))
                throw new \forge\HttpException('Module name not given.',\forge\HttpException::HTTP_BAD_REQUEST);

            if (in_array($_POST['module'],$modules = \forge\components\Software::config('modules'))) {
                foreach ($modules as $key => $module)
                    if ($module == $_POST['module'])
                        unset($modules[$key]);

                \forge\components\Software::config(array('modules'=>$modules));
                \forge\components\Software::configure(true);
            }

            $xml->writeElement('software');
        }

        /**
        * Load a module to the system
        * @return void
        */
        static public function enableModule(\XMLWriter $xml) {
            if (empty($_POST['module']))
                throw new \forge\HttpException('Module name not given.',\forge\HttpException::HTTP_BAD_REQUEST);

            if (!in_array('modules/'.$_POST['module'],glob('modules/*')))
                throw new \forge\HttpException('Module was not found.',\forge\HttpException::HTTP_BAD_REQUEST);

            if (!in_array($_POST['module'],$modules = \forge\components\Software::config('modules'))) {
                \forge\Addon::loadModule($_POST['module']);
                $modules[] = $_POST['module'];
                \forge\components\Software::config(array('modules'=>$modules));
                \forge\components\Software::configure(true);
            }

            $xml->writeElement('software');
        }

        /**
        * Install a new module
        * @return void
        * @throws Exception
        */
        static public function installModule(\XMLWriter $xml) {
            Accounts::Restrict('Software','admin','list','w');

            // Make sure the file was uploaded
            if (!is_uploaded_file(@$_FILES['installModule']['tmp_name']))
                throw new HttpException('NO_FILE_UPLOADED',HttpException::HTTP_BAD_REQUEST);

            // So, install it.
            Software::installModule($_FILES['installModule']['tmp_name']);

            $xml->writeElement('software');
        }
    }