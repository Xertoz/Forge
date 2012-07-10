<?php
	/**
	* com.Templates.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components;

	/**
	* Manage templates
	*/
	class Templates extends \forge\Component implements \forge\components\Dashboard\InfoBox {
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
		static protected $permissions = array(
			'Templates' => array(
				'admin' => array(
					'list'
				)
			)
		);

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
		* Display a template file
		* @param mixed String or array of strings with path(s) to template files to use
		* @param array Template variables to send to the file
		* @return string Parsed template
		* @throws Exception
		*/
		static public function display($files,array $variables=array()) {
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
			if (file_exists($path.$type.'.'.$name.'.css'))
				self::addStyle(implode(array(
					'<style type="text/css" media="screen">',
					\forge\components\Templates\Engine::display($path.$type.'.'.$name.'.css',$tv),
					'</style>'
				)));

			// Add JS
			if (file_exists($path.$type.'.'.$name.'.js'))
				self::addScript(implode(array(
					'<script type="text/javascript">',
					\forge\components\Templates\Engine::display($path.$type.'.'.$name.'.js',$tv),
					'</script>'
				)));

			// Return the template
			if ($type == 'page') {
				if (file_exists('templates/'.self::getTemplate().'/init.php'))
					include 'templates/'.self::getTemplate().'/init.php';

				return self::display(
					'%T/sys.design.php',
					array_merge(
						$variables,
						array('content' => $output)
					)
				);
			}
			else
				return $output;
		}
		
		/**
		 * Get the infobox for the dashboard as HTML source code
		 * @return string
		 */
		static public function getInfoBox() {
			if (!\forge\components\Accounts::getPermission(\forge\components\Accounts::getUserId(),'templates','admin','list','r'))
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
			if (\forge\components\Accounts::isAdmin() && ($template = \forge\Memory::cookie('template')) != null) {
				if (self::isTemplate($template))
					return $template;
				else
					throw new \forge\HttpException('You are requesting a non-existant template',\forge\HttpException::HTTP_NOT_IMPLEMENTED);
			}

			return self::getConfig('templates', array())[$hostname ? $hostname : $_SERVER['HTTP_HOST']];
		}

		/**
		* Get template list
		* @return array
		*/
		static public function getTemplates() {
			foreach (glob('templates/*') as $template)
				require_once $template.'/info/template.php';
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