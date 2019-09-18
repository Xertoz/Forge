<?php
	/**
	 * class.class.ViewHelper.phper.php
	 * Copyright 2019 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge\components\Templates;

	use forge\components\Identity;
	use forge\components\SiteMap;

	class ViewHelper implements \ArrayAccess {
		/**
		 * Get the currently logged in identity
		 * @return Identity\Identity|null
		 * @throws \Exception
		 */
		public function ident() {
			return Identity::getIdentity();
		}

		/**
		 * Figure out if the user is an administrator
		 * @return bool
		 */
		public function isAdmin() {
			return Identity::isAdmin();
		}

		/**
		 * Get the menu object
		 * @return \forge\components\Databases\TableList
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function menu() {
			return SiteMap::getMenu();
		}

		/**
		 * Whether a offset exists
		 * @link https://php.net/manual/en/arrayaccess.offsetexists.php
		 * @param mixed $offset <p>
		 * An offset to check for.
		 * </p>
		 * @return boolean true on success or false on failure.
		 * </p>
		 * <p>
		 * The return value will be casted to boolean if non-boolean was returned.
		 * @since 5.0.0
		 */
		public function offsetExists($offset) {
			return method_exists($this, $offset);
		}

		/**
		 * Offset to retrieve
		 * @link https://php.net/manual/en/arrayaccess.offsetget.php
		 * @param mixed $offset <p>
		 * The offset to retrieve.
		 * </p>
		 * @return mixed Can return all value types.
		 * @since 5.0.0
		 */
		public function offsetGet($offset) {
			return $this->$offset();
		}

		/**
		 * Offset to set
		 * @link https://php.net/manual/en/arrayaccess.offsetset.php
		 * @param mixed $offset <p>
		 * The offset to assign the value to.
		 * </p>
		 * @param mixed $value <p>
		 * The value to set.
		 * </p>
		 * @return void
		 * @since 5.0.0
		 */
		public function offsetSet($offset, $value) {}

		/**
		 * Offset to unset
		 * @link https://php.net/manual/en/arrayaccess.offsetunset.php
		 * @param mixed $offset <p>
		 * The offset to unset.
		 * </p>
		 * @return void
		 * @since 5.0.0
		 */
		public function offsetUnset($offset) {}
	}