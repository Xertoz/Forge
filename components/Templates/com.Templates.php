<?php
	/**
	* com.Templates.php
	* Copyright 2010-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	use forge\components\Admin\MenuItem;
	use \forge\components\Templates\Engine;
	use forge\components\Templates\ViewHelper;

	/**
	* Manage templates
	*/
	class Templates extends \forge\Component implements \forge\components\Admin\Menu {
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
			Engine::addScript($script);
		}

		/**
		* Add some CSS to the header element
		* @param string
		* @return void
		*/
		static public function addStyle($style) {
			Engine::addStyle($style);
		}

		/**
		 * Add an external CSS file to the header element
		 * @param string $file File name
		 * @param bool $preserve Do not minify the source
		 * @return void
		 */
		static public function addStyleFile($file, $preserve=false) {
			Engine::addStyleFile($file, $preserve);
		}

		/**
		 * Display a template file
		 * @param $files
		 * @param array $variables
		 * @return string Parsed template
		 * @throws \forge\HttpException
		 * @deprecated
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
			$output = Engine::display($file,$tv=array_merge(self::$vars,$variables));

			// Add CSS
			if (file_exists($css = $path.$type.'.'.$name.'.css'))
				self::addStyleFile('/'.$css);

			// Add JS
			Engine::requireJS();
			if (file_exists($path.$type.'.'.$name.'.js'))
				Engine::addScriptFile('/'.$path.$type.'.'.$name.'.js');

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
		 * @param \forge\components\SiteMap\db\Page Page
		 * @param string Addon
		 * @param string View
		 * @return MenuItem
		 * @throws \Exception
		 */
		static public function getAdminMenu($page, $addon, $view) {
			if (!\forge\components\Identity::hasPermission('com.Templates.Admin'))
				return null;

			$menu = new MenuItem('developer', self::l('Developer'));

			$menu->appendChild(new MenuItem(
				'templates',
				self::l('Templates'),
				'Templates'
			));

			return $menu;
		}

		/**
		 * Get default template
		 * @param bool $hostname string Hostname to get the template for
		 * @return string
		 * @throws \forge\HttpException
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
			Engine::setMeta($meta);
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
		 * @param bool $save
		 * @param bool $hostname string Hostname to get the template for
		 * @return void
		 * @throws \Exception
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

		/**
		 * Parse a view with given variables through Smarty.
		 * The template's view will be used if it exists, otherwise the file in Addon/tpl will be used.
		 * @param string $view Name of the view to display or "template/view" if a specific template is to be used.
		 * @param array[string => mixed] $vars Array of variables to pass into the view.
		 * @return string Returns the parsed view.
		 * @throws \Exception
		 */
		static public function view(string $view, array $vars=[]) {
			// Figure out if a template was requested
			$view = explode('/', $view);
			$template = count($view) === 1 ? self::getTemplate() : $view[0];
			$view = count($view) === 1 ? $view[0] : $view[1];

			// Who called?
			$ns = explode('/', substr(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['file'], strlen(FORGE_PATH)));
			$addon = $ns[2];

			// Find the view file
			$file = FORGE_PATH.'/templates/'.$template.'/'.$addon.'/'.$view.'.tpl';
			if (!file_exists($file))
				$file = FORGE_PATH.'/'.$ns[1].'/'.$addon.'/tpl/'.$view.'.tpl';
			if (!file_exists($file))
				throw new \Exception('View not found: '.$file);

			// Set up Smarty
			$smarty = new \Smarty();
			$smarty->setTemplateDir(FORGE_PATH.'/templates/'.$template.'/');
			$smarty->setCompileDir(FORGE_PATH.'/files/smarty/'.$template.'_c/');
			$smarty->setConfigDir(FORGE_PATH.'/files/smarty/config/');
			$smarty->setCacheDir(FORGE_PATH.'/files/smarty/cache/');

			// Register some functions
			$smarty->registerPlugin('modifier', 'l', 'forge\\components\\Templates::l');
			$smarty->registerPlugin('function', 'header', function($params) {
				return Engine::header($params['tabs'] ?? 0);
			});
			$smarty->registerPlugin('function', 'input', function($params) {
				return Engine::input($params['type'], $params['name'], $params['value'] ?? null, $params['auto'] ?? true, $params);
			});

			// Assign vars
			foreach (array_merge(self::$vars, $vars) as $key => $mixed)
				$smarty->assign($key, $mixed);

			// Assign the magic forge variable
			$smarty->assign('forge', new ViewHelper);

			// Parse the view and return it
			return $smarty->fetch($file);
		}
	}
