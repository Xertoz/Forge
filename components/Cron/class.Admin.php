<?php
	/**
	* class.Admin.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Cron;

	/**
	* Software component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			\forge\components\Identity::restrict('com.Cron.Admin');

			// Show the view
			return \forge\components\Templates::display('components/Cron/tpl/adm.index.php', ['jobs' => \forge\components\Cron::getJobs()]);
		}
	}