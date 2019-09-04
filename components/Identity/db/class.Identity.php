<?php
	/**
	* class.Identity.php
	* Copyright 2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Identity\db;

	/**
	* Table definition of identities
	*/
	class Identity extends \forge\components\Databases\Table {
		/**
		* Table name
		* @var string
		*/
		static protected $table = 'identities';

		/**
		* We are global
		* @var bool
		*/
		static protected $global = true;
		
		/**
		 * Permissions associated with this identity
		 * @var \forge\components\Databases\TableList
		 */
		private $__permissions = null;

		/**
		 * Master identity in the relationships
		 * @var int
		 */
		public $master = 'Integer';

		/**
		* Class reference which handles this identity
		* @var string
		*/
		public $type = array(
			'Char',
			'length' => 64
		);

		/**
		 * The derived identity class' unique identifier for this identity
		 * @var int
		 */
		public $identifier = 'Integer';

		/**
		 * Override the construct to add loading of permissions
		 */
		public function __construct() {
			call_user_func_array('parent::__construct', func_get_args());
			
			if ($this->getID())
				$this->loadPermissions();
		}
		
		/**
		 * Get all permissions this identity has been granted
		 * @return \forge\components\Databases\TableList
		 */
		public function getPermissions() {
			return $this->__permissions;
		}

		/**
		 * Load permissions
		 */
		private function loadPermissions() {
			if ($this->__permissions !== null)
				return;

			$result = new \forge\components\Databases\TableList([
				'type' => new \forge\components\Identity\db\Permission,
				'where' => ['identity'=>$this->getId()]
			]);

			$this->__permissions = [];
			foreach ($result as $permission)
				$this->__permissions[] = $permission->permission;
		}

		/**
		 * Override the select function to add loading of permissions
		 */
		public function select($columns) {
			call_user_func_array('parent::select', func_get_args());

			if ($this->getID())
				$this->loadPermissions();
		}
	}