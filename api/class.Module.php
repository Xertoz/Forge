<?php
	/**
	* class.Module.php
	* Copyright 2008-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* Supply the module class template
	*/
	abstract class Module extends Addon {
		/**
		* Page definitions
		* @var array
		*/
		static protected $pageTypes = array();

		/**
		* Is it required for templates to specifically support this module?
		* @var bool
		*/
		static protected $templateSensitive = false;

		/**
		 * Get available page definitions
		 * @return array
		 * @throws \Exception
		 */
		static public function getPageTypes() {
			if (!is_array(static::$pageTypes))
				throw new \Exception('Invalid module definition');
			return static::$pageTypes;
		}

		/**
		* Is this module sensitive to what templates invoke it?
		* @return bool
		*/
		static public function isTemplateSensitive() {
			return (bool)static::$templateSensitive;
		}
	}