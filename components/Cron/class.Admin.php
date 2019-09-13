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

	use forge\components\Admin\Administration;
	use forge\components\Cron;
	use forge\components\Identity;
	use forge\components\Templates;
	use forge\components\Templates\DataTable;
	use forge\HttpException;

	/**
	* Software component of Forge 4
	* Administration interface
	*/
	class Admin implements Administration {
		/**
		 * Display the list of cron jobs
		 * @return string
		 * @throws HttpException
		 * @throws \Exception
		 */
		static public function index() {
			Identity::restrict('com.Cron.Admin');

			$jobs = Cron::getJobs();
			$jobs = count($jobs) ? new DataTable($jobs) : null;
			if ($jobs instanceof DataTable) {
				$jobs->setColumns(['name' => 'Name', 'interval' => 'Interval', 'last' => 'Last run']);
				$jobs->setCallbacks([
					'name' => function($r) {
						return (string)$r;
					},
					'interval' => function($r) {
						/**
						 * @var Job $r
						 */
						return $r::getCanonicalInterval();
					},
					'last' => function($r) {
						return date('Y-m-d H:i:s', Cron::getLastRun($r));
					}
				]);
			}

			// Show the view
			return Templates::view('admin_jobs', ['jobs' => $jobs]);
		}
	}