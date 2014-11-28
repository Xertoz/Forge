<?php
	/**
	* com.Templates.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Manage templates
	*/
	class Templates extends \forge\Component implements \forge\components\Admin\Menu, \forge\components\Dashboard\InfoBox {
		use \forge\Configurable;
		
		/**
		* Template information
		* @var array
		*/
		static private $templates = array();

		/**
		* Default template
		* @var string
		*/
		static private $template = null;

		/**
		* Global template variables
		* @var array
		*/
		static private $vars = array();

		/**
		* Permissions
		* @var array
		*/
		static protected $permissions = ['Admin'];

		/**
		* Add some JavaScript to the header element
		* @param string
		* @return void
		*/
		static public function addScript($script) {
			\forge\components\Templates\Engine::addScript($script);
		}

		/**
		* Add some CSS to the header element
		* @param string
		* @return void
		*/
		static public function addStyle($style) {
			\forge\components\Templates\Engine::addStyle($style);
		}
		
		/**
		 * Add an external CSS file to the header element
		 * @param string $file File name
		 * @param bool $preserve Do not minify the source
		 * @return void
		 */
		static public function addStyleFile($file, $preserve=false) {
			\forge\components\Templates\Engine::addStyleFile($file, $preserve);
		}

		/**
		* Display a template file
		* @param mixed String or array of strings with path(s) to template files to use
		* @param array Template variables to send to the file
		* @return string Parsed template
		* @throws Exception
		*/
		static public function display($files,array $variables=array()) {
			if (file_exists(FORGE_PATH.'/templates/'.self::getTemplate().'/init.php'))
				require_once FORGE_PATH.'/templates/'.self::getTemplate().'/init.php';

			// We utilize an array when checking if it exists
			if (!is_array($files))
				$files = array($files);

			// Loop over all requested files and use the first found one
			foreach ($files as $file) {
				$file = str_replace('%T','templates/'.self::getTemplate(),$file);

				if (file_exists($file))
					break;
				else
					$file = false;
			}

			// If we didn't find any file - we should 501!
			if ($file === false)
				throw new \Exception('Template file(s) not found');

			// Get the path & file name etc
			$path = explode('/',$file);
			$name = explode('.',array_pop($path));
			$type = array_shift($name);
			$ext = array_pop($name);
			$name = implode('.',$name);
			$path = implode('/',$path).'/';

			// Try to execute the template code
			$output = \forge\components\Templates\Engine::display($file,$tv=array_merge(self::$vars,$variables));

			// Add CSS
			if (file_exists($css = $path.$type.'.'.$name.'.css'))
				self::addStyleFile('/'.$css);

			// Add JS
			if (file_exists($path.$type.'.'.$name.'.js'))
				self::addScript(\forge\components\Templates\Engine::display($path.$type.'.'.$name.'.js',$tv));

			// Return the template
			if ($type == 'page')
				return self::display(
					'%T/sys.design.php',
					array_merge(
						$variables,
						array('content' => $output)
					)
				);
			else
				return $output;
		}
		
		/**
		 * Get the menu items
		 * @return array[AdminMenu]|MenuItem
		 */
		static public function getAdminMenu() {
			if (!\forge\components\Identity::hasPermission('com.Templates.Admin'))
				return null;
			
			$menu = new \forge\components\Admin\MenuItem('developer', self::l('Developer'));
			
			$menu->appendChild(new \forge\components\Admin\MenuItem(
				'templates',
				self::l('Templates'),
				'/admin/Templates'
			));
			
			return $menu;
		}
		
		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Identity::getIdentity()->hasPermission('com.Templates.Admin'))
				return null;

			return self::display(
				'components/Templates/tpl/inc.infobox.php',
				array(
					'templates' => count(self::getTemplates())
				)
			);
		}

		/**
		* Get default template
		* @param $hostname string Hostname to get the template for
		* @return string
		* @throws Exception
		*/
		static public function getTemplate($hostname=false) {
			if (\forge\components\Identity::isAdmin() && ($template = \forge\Memory::cookie('template')) != null) {
				if (self::isTemplate($template))
					return $template;
				else
					throw new \forge\HttpException('You are requesting a non-existant template',\forge\HttpException::HTTP_NOT_IMPLEMENTED);
			}

			$templates = self::getConfig('templates', array());
			$key = $hostname ? $hostname : $_SERVER['HTTP_HOST'];
			
			return isset($templates[$key]) ? $templates[$key] : 'anvil';
		}

		/**
		* Get template list
		* @return array
		*/
		static public function getTemplates() {
			foreach (glob('templates/*') as $target)
				\forge\Helper::run(function() use ($target) {
					$folder = substr($target, strlen('templates/'));
					$template = new Templates\Template($folder);
					if ($template->isSelectable())
						self::$templates[$folder] = $template;
				});
			ksort(self::$templates);
			
			return self::$templates;
		}

		/**
		* Get template variable
		* @param string Key
		* @return mixed
		*/
		static public function getVar($key) {
			return self::$vars[$key];
		}

		/**
		* Does this template exist?
		* @param string Template system name
		* @return bool
		*/
		static public function isTemplate($sysName) {
			self::getTemplates();

			return isset(self::$templates[$sysName]);
		}

		/**
		* Set META elements
		* @param array META elements
		* @return void
		*/
		static public function setMeta($meta) {
			\forge\components\Templates\Engine::setMeta($meta);
		}

		/**
		* Set template information
		* @param string Template system name
		* @param array Template infomation
		* @return void
		*/
		static public function setTemplateInfo($template,$information) {
			self::$templates[$template] = $information;
		}

		/**
		* Set default template
		* @param string Template system name
		* @param bool Write to config file
		* @param $hostname string Hostname to get the template for
		* @return void
		*/
		static public function setTemplate($template, $save=false, $hostname=false) {
			$templates = self::getConfig('templates', array());
			$templates[$hostname ? $hostname : $_SERVER['HTTP_HOST']] =  $template;
			self::setConfig('templates', $templates, (bool)$save);
		}

		/**
		* Set template variable
		* @param string Key
		* @param string Value
		* @return void
		*/
		static public function setVar($key,$value) {
			self::$vars[$key] = $value;
		}
	}