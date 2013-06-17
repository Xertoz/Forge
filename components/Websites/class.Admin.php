<?php
	/**
	* acp.Websites.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Websites;
	use \forge\components\Databases\TableList;

	/**
	* Allow administration of Websites component
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			\forge\components\Identity::restrict('com.Websites.Admin');

			$websites = new TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\Websites\db\Website
			]));

			return \forge\components\Templates::display('components/Websites/tpl/acp.websites.php',array('websites'=>$websites));
		}
	}