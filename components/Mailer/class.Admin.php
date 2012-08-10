<?php
	/**
	* acp.Mailer.php
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
	* Mailer component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			Accounts::restrict('Mailer','admin','settings','r');

			$mailcfg = array(
				'from' => array(
					'address' => Mailer::getFromAddress(),
					'name' => Mailer::getFromName()
				),
				'smtp' => array(
					'use' => (int)Mailer::getSMTPUsage(),
					'hostname' => Mailer::getSMTPServer(),
					'username' => Mailer::getSMTPUsername(),
					'password' => null
				)
			);

			return \forge\components\Templates::display('components/Mailer/tpl/acp.settings.php', $mailcfg);
		}
	}