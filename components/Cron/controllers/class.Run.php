<?php
	/**
	* class.Run.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Cron\controllers;

	/**
	* Handle page models through HTTP
	*/
	class Run extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Cron.Admin');
			
			$job = \forge\Post::getRegexp('job', '/forge\\\\(controllers|modules)\\\\(\\w+)\\\\jobs\\\\(\\w+)/');
			
			if (class_exists($job) && is_subclass_of($job, 'forge\\components\\Cron\\Job'))
				try {
					$job::run();
					\forge\components\Cron::setLastRun($job);
					self::setResponse(self::l('The job was executed successfully'), self::RESULT_OK);
				}
				catch(\Exception $e) {
					// TODO: Log run errors
					self::setResponse(self::l('An error occured while running the cron job'), self::RESULT_BAD);
				}
			else
				self::setResponse(self::l('The requested cron job does not exist'), self::RESULT_BAD);
		}
	}