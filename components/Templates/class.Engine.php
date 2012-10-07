<?php
	/**
	* class.Engine.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;

	/**
	* The actual template engine
	*/
	class Engine {
		/**
		* META data
		* @var array
		*/
		static private $meta = array();

		/**
		* JavaScript for the header section
		* @var array
		*/
		static private $scripts = array();

		/**
		* CSS for the header section
		* @var array
		*/
		static private $styles = array();

		/**
		* Add some JavaScript to the header element
		* @param string
		* @return void
		*/
		static public function addScript($script) {
			foreach (self::$scripts as $subject)
				if ($subject == $script)
					return;

			self::$scripts[] = $script;
		}
		
		/**
		 * Add an external JavaScript file to the header element
		 * @param string $file File name
		 * @return void
		 */
		static public function addScriptFile($file) {
			$script = '<script src="'.self::html($file).'" type="text/javascript"></script>';
			
			self::addScript($script);
		}

		/**
		* Add some CSS to the header element
		* @param string
		* @return void
		*/
		static public function addStyle($style) {
			foreach (self::$styles as $subject)
				if ($subject == $style)
					return;

			self::$styles[] = $style;
		}
		
		static public function addStyleFile($file) {
			self::addStyle('<link href="'.self::html($file).'" rel="stylesheet" media="screen" />');
		}

		/**
		* Create a date picker instance
		* @param array Template array
		* @return string
		* @throws Exception
		*/
		static public function date($vars) {
			if (empty($vars['name']))
				throw new \Exception('A name must be set for the editor');

			if (empty($vars['value']))
				$vars['value'] = time();
			else
				$vars['value'] = strtotime($vars['value']);

			if (empty($vars['time']))
				$vars['time'] = false;

			$date = '<input type="text" name="'.self::html($vars['name']).'" value="'.date('Y-m-d'.($vars['time']?' H:i:s':null),$vars['value']).'" class="datepicker">';

			return $date;
		}

		/**
		* Display a specific template file with some variables
		* @param string File path to invoke
		* @param array Template variables
		* @return string
		*/
		static public function display($file,array $variables=array()) {
			try {
				ob_start();

				extract($variables);
				require $file;
				$parsed = ob_get_contents();

				ob_end_clean();

				return $parsed;
			}
			catch (\Exception $e) {
				ob_end_clean();
				throw $e;
			}
		}

		/**
		 * Implement one of the available Forge JavaScript APIs
		 * @param $api string Which API should be included?
		 * @return void
		 */
		static public function forgeJS($api) {
			self::addScriptFile('/script/'.$api.'.js');
		}

		/**
		* Get META elements
		* @return array
		*/
		static public function getMeta() {
			return self::$meta;
		}
		
		/**
		 * Get a parameter from the REQUEST input
		 * @param string $name Field name
		 * @param string $default Default value (if no input was sent)
		 * @return string
		 */
		static private function getRequestField($name, $default) {
			preg_match_all('/\w+/', $name, $matches);
			
			$ref = array_merge($_GET, $_POST);
			while ($matches[0])
				$ref = &$ref[array_shift($matches[0])];
			
			return isset($ref) ? $ref : $default;
		}

		/**
		* Get all JavaScript elements as string
		* @return string
		*/
		static public function getScripts() {
			return implode('',self::$scripts);
		}

		/**
		* Get all CSS elements as string
		* @return string
		*/
		static public function getStyles() {
			return implode('',self::$styles);
		}

		/**
		* Get the complete header elements as string
		* @return string
		*/
		static public function header() {
			return implode('',array(
				self::meta(),
				self::getStyles(),
				self::getScripts()
			));
		}

		/**
		* Escape string to HTML
		* @param string Raw input
		* @return string Escaped HTML
		*/
		static public function html($input) {
			return htmlspecialchars($input);
		}

		/**
		* Create an editor instance here
		* @param array Template array
		* @return string
		* @throws Exception
		*/
		static public function editor($vars) {
			if (empty($vars['name']))
				throw new \Exception('A name must be set for the editor');

			if (empty($vars['value']))
				$vars['value'] = null;
			
			self::addScriptFile('http://cdn.aloha-editor.org/latest/lib/vendor/jquery-1.7.2.js');
			self::addScriptFile('http://cdn.aloha-editor.org/latest/lib/require.js');
			self::addScript(
				'<script src="http://cdn.aloha-editor.org/latest/lib/aloha.js"
					data-aloha-plugins="common/ui,
					common/format,
					common/list,
					common/link,
					common/highlighteditables,
					common/table"></script>'
			);
			self::addScript(
				'<script type="text/javascript">
					Aloha.ready(function() {
						var $ = Aloha.jQuery;
						$(".editable").aloha();
					});
				</script>'
			);
			self::addStyleFile('http://cdn.aloha-editor.org/latest/css/aloha.css');
			
			$id = uniqid();
			$textarea = '<textarea name="'.htmlspecialchars($vars['name']).'" class="editable" id="'.$id.'">'.htmlentities($vars['value']).'</textarea>';

			return $textarea;
		}
		
		/**
		 * Create an INPUT element based on default values and POST/GET data
		 * @param $type string Type attribute
		 * @param $name string Name attribute
		 * @param $auto bool Update the value according to client input?
		 * @param $value string Value attribute
		 * @param $attr array Additional key-value array of attributes
		 * @return string
		 */
		static public function input($type, $name, $value=null, $auto=true, $attr=array()) {
			$attr['name'] = $name;
			$attr['type'] = $type;
			
			switch ($attr['type']) {
				case 'password':
					$attr['value'] = null;
					break;
				
				case 'checkbox':
					$attr['value'] = 1;
					
					if ($auto && (self::getRequestField($name, false) !== false || $value))
						$attr['checked'] = 'checked';
					
					break;
					
				case 'radio':
					$attr['value'] = $value;
					
					if ($auto && self::getRequestField($name, false) === $value)
						$attr['checked'] = 'checked';
					
					break;
				
				default:
					$attr['value'] = $auto ? self::getRequestField($name, $value) : $value;
					break;
			}
			
			return self::display('components/Templates/tpl/inc.input.php', ['attributes' => $attr]);
		}

		/**
		* Get META elements
		* @return string
		*/
		static public function meta() {
			if (!is_array($meta = self::getMeta()))
				return null;

			$output = null;
			foreach ($meta as $key => $value)
				$output .= '<meta name="'.self::html($key).'" content="'.self::html($value).'" />';

			return $output;
		}
		
		/**
		 * Handle any response from a specific controller
		 * @param string $controller
		 * @return string
		 */
		static public function response($controller) {
			$html = null;
			
			if (\forge\Controller::getController() == $controller && \forge\Controller::getCode() != \forge\Controller::RESULT_PENDING) {
				$class = \forge\Controller::getCode() == \forge\Controller::RESULT_OK ? 'success' : 'error';
				$html = '<p class="controller '.$class.'">'.self::html(\forge\Controller::getMessage()).'</p>';
			}
			
			return $html;
		}

		/**
		* Set META elements
		* @param array META elements
		* @return void
		*/
		static public function setMeta($meta) {
			self::$meta = array_merge(self::$meta,$meta);
		}

		/**
		* Get thumbnail href
		* @param string File path relative to site root
		* @param int Width
		* @param int Height
		* @return string Thumbnail path
		*/
		static public function thumb($file,$width,$height) {
			return '/thumbnail/'.$width.'/'.$height.'/'.$file;
		}
	}