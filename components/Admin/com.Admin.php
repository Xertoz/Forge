<?php
	/**
	* com.Admin.php
	* Copyright 2011-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	use forge\components\Admin\Menu;
	use forge\components\Admin\MenuItem;
	use forge\components\Templates\Engine;

	/**
	* Administration component
	*/
	final class Admin extends \forge\Component implements Menu {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		 * Get the menu items
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 */
		static public function getAdminMenu($page, $addon, $view) {
			$menu = new MenuItem('dashboard', self::l('Dashboard'), '/'.Templates::getVar('page')->page_url, 'fa fa-dashboard');

			if ($addon === '\\forge\\components\\Admin')
				$menu->setActive();

			return $menu;
		}

		/**
		 * Display the administration panel
		 * @param $page
		 * @param $addon
		 * @param $view
		 * @return string
		 * @throws \forge\HttpException
		 */
		static public function display($page, $addon,$view) {
			// Explicitly force authentication
			\forge\components\Identity::auth();

			// Then the admin permission is required to continue
			\forge\components\Identity::restrict('com.Admin.Admin');

			// Argument typing
			$addon = (string)$addon;
			$view = (string)$view;

			// Determine the CSS class of the page
			$css = $addon.' '.$view;

			// First off, find the component or module. If it cannot be found, show the 404 page and what not.
			if (\forge\Addon::existsComponent($addon))
				$addon = '\forge\components\\'.$addon;
			elseif (\forge\Addon::existsModule($addon))
				$addon = '\forge\modules\\'.$addon;
			else
				throw new \forge\HttpException('Addon '.$addon.' was not found',\forge\HttpException::HTTP_NOT_FOUND);

			// Does it implement an administration interface?
			if (!class_exists($class = $addon.'\\Admin'))
				throw new \forge\HttpException('The addon does not implement an administration interface',\forge\HttpException::HTTP_NOT_FOUND);
			if (!in_array('forge\components\Admin\Administration', class_implements($class)))
				throw new \forge\HttpException('The addon did not invoke a proper admin interface',\forge\HttpException::HTTP_NOT_IMPLEMENTED);

			// Populate the menu with items
			$menu = [];
			foreach (\forge\components\Software::getAddons(true) as $addon2)
				if (class_exists($addon2) && in_array('forge\\components\\Admin\\Menu', class_implements($addon2))) {
					$items = $addon2::getAdminMenu($page, $addon, $view);
					if (!is_array($items)) {
						if ($items instanceof \forge\components\Admin\MenuItem)
							$items = [$items];
						else
							continue;
					}
					
					foreach ($items as $item)
						if (!isset($menu[$item->getName()]))
							$menu[$item->getName()] = $item;
						else
							$menu[$item->getName()]->merge($item);
				}
			
			// If the view exists, then we should return the administration output
			if (method_exists($class,$view)) {
				Engine::requireJS();

				return Templates::view('forge-admin/view', [
					'admin' => true,
					'css' => $css,
					'ident' => Identity::getIdentity(),
					'menu' => $menu,
					'view' => $class::$view()
				]);
			}

			// If we haven't reached the return line above, we've 404'd  the view
			throw new \forge\HttpException('View was not found',\forge\HttpException::HTTP_NOT_FOUND);
		}
	}