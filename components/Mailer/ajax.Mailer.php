<?php
    /**
    * ajax.Mailer.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Mailer;
    use \forge\components\Mailer;
    use \forge\components\Accounts;
    use \forge\HttpException;

    /**
    * Component Mailer's AJAX callback
    */
    class Ajax extends \forge\components\XML\controllers\XML {
        /**
        * Set settings
        * @param XMLWriter
        * @return void
        * @throws Exception
        */
        static public function settings(\XMLWriter $xml) {
            Accounts::restrict('Mailer','admin','settings','w');

            // Begin the config
            $cfg = array();

            // Get from address & name
            if (empty($_REQUEST['mailSettings']['address']['name']))
                throw new HttpException('ADDRESS_NO_NAME',HttpException::HTTP_BAD_REQUEST);
            if (empty($_REQUEST['mailSettings']['address']['from']))
                throw new HttpException('ADDRESS_NO_FROM',HttpException::HTTP_BAD_REQUEST);
            $cfg['MAILER_FROM_NAME'] = $_REQUEST['mailSettings']['address']['name'];
            $cfg['MAILER_FROM_MAIL'] = $_REQUEST['mailSettings']['address']['from'];

            // Get SMTP settings?
            if (isset($_REQUEST['mailSettings']['server']['smtp'])) {
                $cfg['MAILER_SERVER_SMTP'] = 1;

                if (empty($_REQUEST['mailSettings']['server']['hostname']))
                    throw new HttpException('ADDRESS_NO_FROM',HttpException::HTTP_BAD_REQUEST);
                $cfg['MAILER_SERVER_HOSTNAME'] = $_REQUEST['mailSettings']['server']['hostname'];

                if (!empty($_REQUEST['mailSettings']['server']['username']))
                    $cfg['MAILER_SERVER_USERNAME'] = $_REQUEST['mailSettings']['server']['username'];

                if (!empty($_REQUEST['mailSettings']['server']['password']))
                    $cfg['MAILER_SERVER_PASSWORD'] = $_REQUEST['mailSettings']['server']['password'];
            }
            else {
                $cfg['MAILER_SERVER_SMTP'] = 0;
                $cfg['MAILER_SERVER_HOSTNAME'] = null;
                $cfg['MAILER_SERVER_USERNAME'] = null;
                $cfg['MAILER_SERVER_PASSWORD'] = null;
            }

            \forge\components\Mailer::config($cfg);
            \forge\components\Mailer::configure(true);

            $xml->writeElement('mailer');
            $xml->writeAttribute('saved',true);
        }
    }