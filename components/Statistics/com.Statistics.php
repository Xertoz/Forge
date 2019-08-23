<?php
	/**
	* com.Statistics.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Statistics component
	*/
	class Statistics extends \forge\Component {
		static public function runCount() {
			if (session_id() != '') {
				$visitor = new Statistics\db\Visitor;
				$visitor->session_id = session_id();
				
				try {
					$visitor->select('session_id');
				}
				catch (\forge\components\Databases\exceptions\NoData $e) {}
				
				$visitor->ip = $_SERVER['REMOTE_ADDR'];
				if (\forge\components\Identity::isAuthenticated())
					$visitor->identity = \forge\components\Identity::getIdentity()->getId();
				
				$visitor->write(true);
			}
		}
	}