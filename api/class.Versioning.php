<?php
	/**
	* trait.Versioning.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* Add version support for classes
	*/
	trait Versioning {
		/**
		* The version number
		* @var string
		*/
		static protected $version = null;

		/**
		* Get the version number
		* @return string
		*/
		static final public function getVersion() {
			return static::$version;
		}
	}