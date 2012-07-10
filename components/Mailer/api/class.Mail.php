<?php
	/**
	* class.Mail.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Mailer;

	require_once 'components/Mailer/api/PHPMailer_v5.1/class.phpmailer.php';
	require_once 'components/Mailer/api/PHPMailer_v5.1/class.smtp.php';

	/**
	* Mailman
	* This is simply an extension of PHPMailer for functionality in Forge
	*/
	class Mail extends \PHPMailer {
		/**
		* @desc Load variables from registry
		* @return void
		*/
		public function __construct() {
			// Set from address & name
			$this->From = \forge\components\Mailer::getFromAddress();
			$this->FromName = \forge\components\Mailer::getFromName();

			// UTF8!
			$this->CharSet = 'UTF-8';

			// Set local mailer
			if (\forge\components\Mailer::getSMTPUsage()) {
				$this->IsSMTP();
				$this->SMTPAuth = strlen(\forge\components\Mailer::getSMTPUsername()) ? true : false;
				$this->Host = \forge\components\Mailer::getSMTPServer();
				$this->Username = \forge\components\Mailer::getSMTPUsername();
				$this->Password = \forge\components\Mailer::getSMTPPassword();
			}
			else
				$this->IsMail();

			// Set to be HTML
			$this->IsHTML(true);
		}
	}