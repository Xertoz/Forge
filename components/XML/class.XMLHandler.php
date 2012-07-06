<?php
    /**
    * class.XMLHandler.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\XML;

    /**
    * The XML view
    */
    class XMLHandler extends \forge\RequestHandler {
        /**
        * Initialize the request
        */
        public function handle() {
            // Get a writer
            $xml = \forge\components\XML::createWriter();

            // Try to find the feed and throw proper HTTP errors if it fails
            try {
                // Find out what addon and method to run
                preg_match('/^(\w+)\/(\w+)$/D',$this->getPath(),$matches);
                list($addon,$method) = array_splice($matches,1);
                unset($matches);

                // Find the addon
                if (\forge\Addon::existsComponent($addon))
                    $class = \forge\Addon::getComponent($addon);
                elseif (\forge\Addon::existsModule($addon))
                    $class = \forge\Addon::getModule($addon);
                else
                    throw new \forge\HttpException('The requested feed does not exist',\forge\HttpException::HTTP_NOT_FOUND);

                // Find its AJAX definition
                if (!class_exists($ajax = call_user_func($class.'::getName',true).'\Ajax'))
                    throw new \forge\HttpException('AJAX definition not found',\forge\HttpException::HTTP_NOT_IMPLEMENTED);

                // Does the method exist?
                if (!method_exists($ajax,$method))
                    throw new \forge\HttpException('AJAX method not found',\forge\HttpException::HTTP_NOT_IMPLEMENTED);
            }
            catch (\forge\HttpException $e) {
                throw $e;
            }
            catch (\Exception $e) {
                throw new \forge\HttpException('URL not found',\forge\HttpException::HTTP_NOT_FOUND);
            }

            // We know where it is - time to try and execute it
            try {
                call_user_func($ajax.'::'.$method,$xml);
            }
            catch (\forge\HttpException $e) {
                switch ($e->getCode()) {
                    default:
                        throw $e;

                    case \forge\HttpException::HTTP_BAD_REQUEST:
                    case \forge\HttpException::HTTP_FORBIDDEN:
                        $xml = \forge\components\XML::createWriter();

                        $xml->startElement('forge');
                        $xml->startElement('error');
                        $xml->writeElement('message',$e->getMessage());
                        $xml->endElement();
                        $xml->endElement();

                        header('HTTP/1.1 400 Bad Request',true,400);
                }
            }
            catch (\Exception $e) {
                throw $e;
            }

            // Set the output XML
            $this->setContentType('text/xml;charset=UTF-8');
            echo $xml->outputMemory();
        }
    }