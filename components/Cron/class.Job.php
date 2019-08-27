<?php
	/**
	* intf.Job.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Cron;

	/**
	* Any addon who wish to append itself to the dashboard must implement this interface
	*/
	abstract class Job {
		use \forge\components\Locale\Translator;
		
		const MINUTE = 60;
		const QUARTER = 900;
		const HOUR = 3600;
		const DAY = 86400;
		const WEEK = 345600;
		const MONTH = 2592000;
		const YEAR = 31536000;
		
		/**
		 * Define how many seconds need to be passed since last run to fire again. (Use one of the constants)
		 * @var int
		 */
		static protected $every = self::MINUTE;
		
		/**
		 * Get the interval as a human text
		 * @return string
		 */
		static public function getCanonicalInterval() {
			switch (static::$every) {
				case self::MINUTE: return self::l('Minute');
				case self::QUARTER: return self::l('Quarter');
				case self::HOUR: return self::l('Hourly');
				case self::DAY: return self::l('Daily');
				case self::WEEK: return self::l('Weekly');
				case self::MONTH: return self::l('Monthly');
				case self::YEAR: return self::l('Yearly');
				default: return self::l('Custom').' ('.static::$every.')';
			}
		}
		
		/**
		 * Figure out wether or not this job is ready to run given its last runtime
		 * @var int $date
		 * @return boolean
		 */
		static public function isScheduled(int $last) {
			return static::$every+$last < time();
		}
		
		/**
		 * Run the job
		 * @return string
		 */
		abstract static public function run();
	}