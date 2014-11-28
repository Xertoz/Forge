<?php
	/**
	 * class.Provider.php
	 * Copyright 2013 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

namespace forge\components\Identity;

/**
 * A provider adds support to specific login systems for identities.
 */
abstract class Provider implements ProviderInterface {
	use \forge\components\Locale\Translator;

	/**
	 * The provider id
	 * @var int
	 */
	public $identifier;

	/**
	 * Load a new provider from its unique id
	 * @param $identifier
	 * @return \forge\components\Identity\Provider
	 */
	public function __construct($identifier) {
		$this->identifier = (int)$identifier;
	}

	/**
	 * Create a new identity from the provider
	 * @param int $identifier The provider's unique id for this identity
	 * @return \forge\components\Identity\Identity
	 */
	final static protected function createIdentity($identifier) {
		$identity = new db\Identity();
		$identity->type = get_called_class();
		$identity->identifier = $identifier;
		\forge\Helper::run(function() use ($identity) { $identity->select(['type', 'identifier']); });
		$identity->write();

		return new Identity($identity->getId());
	}

	/**
	 * Get the provider id
	 * @return int
	 */
	public function getId() {
		return $this->identifier;
	}

	/**
	 * Get the identity instance for a provider
	 * @param int $identifier
	 * @return Identity
	 */
	static public function getIdentity($identifier) {
		$identity = new db\Identity();
		$identity->type = get_called_class();
		$identity->identifier = $identifier;
		$identity->select(['type', 'identifier']);

		return new Identity($identity->getId());
	}

	/**
	 * This is called when the Identity component decides to log out an identity with this provider
	 */
	static public function logout() {}
}