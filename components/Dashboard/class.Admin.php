<?php
	/**
	* class.Admin.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Dashboard;

	/**
	* Dashboard component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			$infoboxes = array();

			foreach (\forge\Addon::getAddons(true) as $addon)
				if (in_array('forge\components\Dashboard\InfoBox', class_implements($addon)))
					try {
						$infoboxes[] = $addon::getInfoBox();
					}
					catch (\Exception $e) {
						$parts = explode('\\', $addon);
						$title = array_pop($parts);
						$infoboxes[] = \forge\components\Templates::display(
							['components/Dashboard/tpl/inc.errorbox.php'],
							['title' => $title]
						);
					}

			return \forge\components\Templates::display(
				array(
					'components/Dashboard/tpl/adm.gui.php'
				),
				array(
					'infoboxes'=>$infoboxes
				)
			);
		}
	}