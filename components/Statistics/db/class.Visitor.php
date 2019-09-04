<?php
	/**
	* class.Visitor.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Statistics\db;

	/**
	* Define a single visitor for a session
	*/
	class Visitor extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'stats_visitors';

		/**
		* Session ID
		* @var string
		*/
		public $session_id = [
			'Char',
			'length' => 40,
			'unique' => true,
			'default' => null,
			'null' => false
		];

		/**
		* First visit
		* @var string
		*/
		public $arrived = 'DateTime';

		/**
		* Last visit
		* @var string
		*/
		public $departed = 'DateTime';
		
		/**
		 * Logged into identity?
		 * @var \forge\components\Identity\db\Identity
		 */
		public $identity = [
			'Foreign',
			'model' => 'Identity.Identity'
		];
		
		/**
		 * Client IP
		 * @var string
		 */
		public $ip = [
			'Char',
			'length' => 15
		];
		
		/**
		 * Number of visits
		 */
		public $visits = [
			'Integer',
			'default' => 1
		];

		/**
		 * Initiate this visitor before writing to database
		 * @return void
		 * @throws \Exception
		 */
		protected function beforeInsert() {
			$this->__set('visits', 1);
			$this->__set('arrived', date('Y-m-d H:i:s'));
		}

		/**
		 * Update this visitor before writing to database
		 * @return void
		 * @throws \Exception
		 */
		protected function beforeSave() {
			$this->__set('visits', $this->__get('visits')+1);
			$this->__set('departed', date('Y-m-d H:i:s'));
		}
	}