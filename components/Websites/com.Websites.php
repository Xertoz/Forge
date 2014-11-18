<?php
	/**
	* com.Websites.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Enable Forge to provide multiple website support on one installation
	*/
	class Websites extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Dashboard\InfoBox {
		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		* The website we're currently on
		* @var \forge\components\Websites\db\tables\Website
		*/
		static private $website = null;
		
		/**
		 * Get the menu items
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu() {
			if (!\forge\components\Identity::hasPermission('com.Websites.Admin'))
				return null;
			
			$menu = new \forge\components\Admin\MenuItem('developer', _('Developer'));
			
			$menu->appendChild(new \forge\components\Admin\MenuItem(
				'websites',
				_('Websites'),
				'/admin/Websites'
			));
			
			return $menu;
		}

		/**
		* Get the domain of the current website
		* @return string Website domain
		*/
		static public function getDomain() {
			self::loadWebsite();
			
			return self::$website->domain;
		}

		/**
		* Get all available domains on this system
		* @param bool Get alias domans too?
		* @return \forge\components\Databases\TableList
		*/
		static public function getDomains($includeAlias = false) {
			$conditions = array();

			if ($includeAlias === false)
				$conditions['alias'] = null;

			return new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\Websites\db\Website,
				'where' => $conditions
			]));
		}

		/**
		* Get id of the domain
		* @param string Website domain
		* @return int Website ID
		*/
		static public function getId($domain=null) {
			self::loadWebsite();
			
			if (is_null($domain) || $domain === false)
				return self::$website instanceof \forge\components\Websites\db\Website ? self::$website->getId() : 0;

			$website = new \forge\components\Websites\db\Website;
			$website->domain = $domain;
			$website->select('domain');

			return $website->getId();
		}
		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Websites.Admin'))
				return null;

			return \forge\components\Templates::display(
				'components/Websites/tpl/inc.infobox.php',
				array(
					'websites' => \forge\components\Websites::getDomains(true)->length()
				)
			);
		}
		
		/**
		 * Make sure we have a valid host loaded
		 * @return void
		 */
		static public function loadWebsite() {
			if (self::$website == null)
				try {
					self::$website = new \forge\components\Websites\db\Website;
					self::$website->domain = $_SERVER['HTTP_HOST'];
					self::$website->select('domain');
					
					if (strlen(self::$website->alias) > 0)
						\forge\components\SiteMap::redirect('http://'.self::$website->alias.$_SERVER['REQUEST_URI'], 301);
				}
				catch (\Exception $e){
					throw new \forge\HttpException('Website not found',\forge\HttpException::HTTP_NOT_FOUND);
				}
			}
	}