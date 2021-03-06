<?php
	/**
	* class.Admin.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Mailer;

	/**
	* Mailer component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			\forge\components\Identity::restrict('com.Mailer.Admin');

			$mailcfg = array(
				'from' => array(
					'address' => \forge\components\Mailer::getFromAddress(),
					'name' => \forge\components\Mailer::getFromName()
				),
				'smtp' => array(
					'use' => \forge\components\Mailer::getSMTPUsage(),
					'hostname' => \forge\components\Mailer::getSMTPServer(),
					'username' => \forge\components\Mailer::getSMTPUsername(),
					'password' => null
				)
			);

			return \forge\components\Templates::display('components/Mailer/tpl/acp.settings.php', $mailcfg);
		}
	}