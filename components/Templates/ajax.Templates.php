<?php
	/**
	* ajax.Templates.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;
	use \forge\components\Templates;
	use \forge\components\Accounts;
	use \Exception;

	require_once 'components/Templates/ajax.Templates.php';

	/**
	* Manage templates (AJAX)
	*/
	class Ajax extends \forge\components\XML\controllers\XML {
		/**
		* Set default template
		* @return void
		*/
		static public function setTemplate(\XMLWriter $xml) {
			Accounts::Restrict('Templates','admin','list','w');

			if (empty($_POST['setTemplate']['default']))
				throw new \forge\HttpException('NO_DEFAULT',\forge\HttpException::HTTP_BAD_REQUEST);

			Templates::setTemplate($_POST['setTemplate']['default'],true);

			$xml->startElement('templates');
			$xml->writeElement('default',$_POST['setTemplate']['default']);
			$xml->endElement();
		}
	}