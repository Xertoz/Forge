<?php
    /**
    * ajax.Databases.php
    * Copyright 2009-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Databases;
    use \forge\components\Databases;
    use \forge\components\Accounts;
    use \forge\HttpException;
    use \Exception;

    /**
    * Database AJAX
    */
    class Ajax extends \forge\components\XML\controllers\XML {
        /**
        * Create a new connection
        * @return void
        * @throws Exception
        */
        static public function create(\XMLWriter $xml) {
            Accounts::Restrict('Databases','admin','list','w');

            // Require a type.
            if (empty($_REQUEST['newConnection']['system']))
                throw new HttpException('COM_DATABASES_NEW_NO_SYSTEM',HttpException::HTTP_BAD_REQUEST);

            // Require a hostname.
            if (empty($_REQUEST['newConnection']['hostname']))
                throw new HttpException('COM_DATABASES_NEW_NO_HOSTNAME',HttpException::HTTP_BAD_REQUEST);

            // Require a database.
            if (empty($_REQUEST['newConnection']['database']))
                throw new HttpException('COM_DATABASES_NEW_NO_DATABASE',HttpException::HTTP_BAD_REQUEST);

            // Require a table prefix.
            if (empty($_REQUEST['newConnection']['prefix']))
                throw new HttpException('COM_DATABASES_NEW_NO_PREFIX',HttpException::HTTP_BAD_REQUEST);

            // Require a type.
            if (empty($_REQUEST['newConnection']['username']))
                throw new HttpException('COM_DATABASES_NEW_NO_USERNAME',HttpException::HTTP_BAD_REQUEST);

            // Require a type.
            if (empty($_REQUEST['newConnection']['password']))
                throw new HttpException('COM_DATABASES_NEW_NO_PASSWORD',HttpException::HTTP_BAD_REQUEST);

            // Add it
            try {
                Databases::addConnection(
                    $_REQUEST['newConnection']['system'],
                    $_REQUEST['newConnection']['hostname'],
                    $_REQUEST['newConnection']['database'],
                    $_REQUEST['newConnection']['prefix'],
                    $_REQUEST['newConnection']['username'],
                    $_REQUEST['newConnection']['password']
                );
            }
            catch (Exception $e) {
                throw new HttpException('COM_DATABASES_NEW_ACCESS_DENIED',HttpException::HTTP_BAD_REQUEST);
            }

            // We're cool, say so.
            $xml->writeElement('database');
        }

        /**
        * Delete a connection
        * @return void
        * @throws Exception
        */
        static public function deleteConnection(\XMLWriter $xml) {
            Accounts::Restrict('Databases','admin','list','w');

            // We must set something
            if (empty($_REQUEST['ConnectionId']))
                throw new Exception('Bad request');

            Databases::deleteConnection($_REQUEST['ConnectionId']);

            $xml->writeElement('databases');
            $xml->writeAttribute('action','delete');
            $xml->writeAttribute('id',$_REQUEST['ConnectionId']);
        }

        /**
        * Modify the database to reflect the module XML
        * @return void
        */
        static public function fixDatabase(\XMLWriter $xml) {
            Accounts::Restrict('Databases','admin','list','w');

            // We do require a name and type
            if (empty($_REQUEST['name']) || empty($_REQUEST['type']))
                throw new \forge\HttpException('Name & type not set',\forge\HttpException::HTTP_BAD_REQUEST);

            // Now that we do have a component, run it
            \forge\components\Databases::fixDatabase($_REQUEST['name'],$_REQUEST['type']);

            $xml->writeElement('forge');
        }

        /**
        * Select the connection!
        * @return void
        * @throws Exception
        */
        static public function selectConnection(\XMLWriter $xml) {
            Accounts::Restrict('Databases','admin','list','w');

            // We must set something
            if (empty($_REQUEST['ConnectionId']))
                throw new Exception('Bad request');

            // Set it
            \forge\components\Databases::setDefaultConnection($_REQUEST['ConnectionId']);

            $xml->writeElement('databases');
            $xml->writeAttribute('action','select');
            $xml->writeAttribute('id',$_REQUEST['ConnectionId']);
        }
    }