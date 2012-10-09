<?php
	/**
	* ctrl.XML.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\XML;

	/**
	* Base class for controllers on /xml
	*/
	class XML {
		static public function controller(\XMLWriter $xml) {
			$xml->startElement('controller');
			$xml->writeElement('name', \forge\Controller::getController());
			$xml->writeElement('code', \forge\Controller::getCode());
			$xml->writeElement('message', \forge\Controller::getMessage());
			
			if (($e = \forge\Controller::getException()) != null) {
				$xml->writeElement('exception', $e->getMessage());
				header($e->getHttpHeader(), $e->getCode());
			}
			
			$xml->endElement();
		}
	}