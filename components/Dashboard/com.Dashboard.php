<?php
	/**
	* com.Dashboard.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;
	use \forge\Component;

	/**
	* Dashboard component
	*/
	class Dashboard extends Component {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = array(
			'Databases' => array(
				'admin' => array(
					'gui'
				)
			)
		);
	}