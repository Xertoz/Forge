<?php
	/**
	 * class.Identity.php
	 * Copyright 2013 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

namespace forge\components\Identity;

/**
 * Represent a set of providers which give authorization support to Forge
 */
class Identity {
	/**
	 * The data relevant to this identity
	 * @var db\Identity
	 */
	private $identity;

	/**
	 * A list of all providers bound to this identity
	 * @var \forge\components\Identity\Provider[]
	 */
	private $providers = [];

	/**
	 * Initialize an identity from one of its ids
	 * @param int $id
	 */
	public function __construct($id) {
		$this->identity = new db\Identity($id);

		if ($this->identity->master > 0)
			$this->identity = new db\Identity($this->identity->master);

		$cls = $this->identity->type;
		$this->providers[] = new $cls($this->identity->identifier);

		$bound = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
			'type' => new db\Identity(),
			'where' => ['master' => $this->identity->getId()]
		]));

		foreach ($bound as $item) {
			$cls = $item->type;
			$this->providers[] = new $cls($item->identifier);
		}
	}

	/**
	 * Bind another identity to this identity
	 * @param Identity $identity
	 * @return void
	 */
	public function bind(Identity $identity) {
		foreach ($identity->getProviders() as $provider) {
			$row = new db\Identity();
			$row->type = get_class($provider);
			$row->identifier = $provider->getId();
			$row->select(['type', 'identifier']);
			$row->master = $this->getId();
			$row->save();
		}

		/**
		 * @var \forge\components\Identity\db\Permission[] $permissions
		 */
		$permissions = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
			'type' => new \forge\components\Identity\db\Permission(),
			'where' => ['identity' => $identity->getId()]
		]));
		foreach ($permissions as $permission) {
			$permission->identity = $this->getId();
			$permission->save();
		}
	}

	/**
	 * Get the email of this identity
	 * @return string
	 */
	public function getEmail() {
		return $this->providers[0]->getEmail();
	}

	/**
	 * Get the id of this identity
	 * @return int
	 */
	final public function getId() {
		return $this->identity->getId();
	}

	/**
	 * Get the full name of this identity
	 * @return string
	 */
	public function getName() {
		return $this->providers[0]->getName();
	}

	/**
	 * Get the title(s) of the provider(s) for this identity
	 * @return string
	 */
	public function getTitle() {
		$titles = [];

		foreach ($this->providers as $provider)
			$titles[] = $provider->getTitle();

		return implode(', ', $titles);
	}

	/**
	 * Get all permissions granted to this identity
	 * @return \forge\components\Identity\db\Permission[]
	 */
	public function getPermissions() {
		return $this->identity->getPermissions();
	}

	/**
	 * Get all providers active on this identity
	 * @return array|Provider[]
	 */
	public function getProviders() {
		return $this->providers;
	}

	/**
	 * Check if the identity has a specific permission
	 * @param string $permission
	 * @return bool
	 */
	final public function hasPermission($permission) {
		return in_array($permission, $this->identity->getPermissions());
	}
}