<?php
	/**
	* com.Dashboard.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	use forge\components\Admin\MenuItem;

	/**
	* Cron component
	*/
	class Cron extends \forge\Component implements \forge\components\Admin\Menu {
		use \forge\Configurable;
		
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Cron.Admin'))
				return null;
			
			$menu = new MenuItem('developer', self::l('Developer'));
			
			$menu->appendChild(new MenuItem(
				'cron',
				self::l('Cron jobs'),
				'Cron'
			));
			
			return $menu;
		}
		
		/**
		 * Get a list of availabe jobs
		 * @return array[string]
		 */
		static public function getJobs() {
			$jobs = [];
			
			foreach (\forge\Addon::getAddons(true) as $addon)
				foreach($addon::getNamespace('jobs') as $job)
					if (class_exists($job) && is_subclass_of($job, 'forge\\components\\Cron\\Job'))
						$jobs[] = $job;
			
			return $jobs;
		}
		
		/**
		 * Get the last time a job were run
		 * @var string $job
		 * @return int
		 */
		static public function getLastRun($job) {
			return \forge\components\Cron::getConfig('job.'.$job, 0);
		}

		/**
		 * Set the last time a job were run
		 *
		 * @return void
		 * @throws \Exception
		 * @var string $job
		 */
		static public function setLastRun($job) {
			return \forge\components\Cron::setConfig('job.'.$job, time(), true);
		}

		/**
		 * Run scheduled cron jobs
		 * @return void
		 * @throws \Exception
		 */
		static public function runJobs() {
			// Loop jobs and run those that are scheduled
			foreach (self::getJobs() as $job)
				if ($job::isScheduled(self::getLastRun($job))) {
					try {
						$job::run();
					}
					catch (\Exception $e) {
						// TODO: Log failed jobs
					}
					
					self::setLastRun($job);
				}
		}
	}