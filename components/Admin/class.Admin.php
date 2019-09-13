<?php
	/**
	 * class.Admin.php
	 * Copyright 2019 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge\components\Admin;

	use forge\Addon;
	use forge\components\Templates;

	/**
	* Dashboard component of Forge 4
	* Administration interface
	*/
	class Admin implements Administration {
		/**
		 * Display the dashboard
		 * @return string
		 * @throws \Exception
		 */
		static public function index() {
			$infoboxes = array();

			foreach (Addon::getAddons(true) as $addon)
				if (class_exists($addon) && in_array('forge\components\Admin\InfoBox', class_implements($addon)))
					try {
						$infoboxes[] = $addon::getInfoBox();
					}
					catch (\Exception $e) {
						$parts = explode('\\', $addon);
						$title = array_pop($parts);
						$infoboxes[] = Templates::view('errorbox', ['title' => $title]);
					}

			return Templates::view('admin_dashboard', ['infoboxes' => $infoboxes]);
		}
	}