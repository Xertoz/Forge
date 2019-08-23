<?php
	/**
	* class.RequireJS.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;

	/**
	* Any addon that has requirejs modules must defined them in this interface
	*/
	interface RequireJS {
		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return array[string => string]
		 */
		static public function getRequireJS();
	}
