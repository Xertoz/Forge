<?php
	/**
	* class.Engine.php
	* Copyright 2011-2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;

	use forge\components\Templates;

	/**
	* The actual template engine
	*/
	class Engine {
		use \forge\components\Locale\Translator;

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
		 * The page title
		 * @var string
		 */
		static private $title = null;

		/**
		* Add some JavaScript to the header element
		* @param string
		* @return void
		*/
		static public function addScript($script) {
			if (is_string($script))
				$script = new JavaScript($script);

			foreach (self::$scripts as $subject)
				if ($subject->getHash() == $script->getHash())
					return;

			self::$scripts[] = $script;
		}

		/**
		 * Add an external JavaScript file to the header element
		 * @param string $file File name
		 * @param bool $preserve Do not minify the source
		 * @return void
		 */
		static public function addScriptFile($file, $preserve=false) {
			self::addScript(new JavaScript($file, JavaScript::TYPE_FILE, !$preserve));
		}

		/**
		* Add some CSS to the header element
		* @param string
		* @return void
		*/
		static public function addStyle($style) {
			if (is_string($style))
				$style = new CSS($style);

			foreach (self::$styles as $subject)
				if ($subject->getHash() == $style->getHash())
					return;

			self::$styles[] = $style;
		}

		/**
		 * Add an external CSS file to the header element
		 * @param string $file File name
		 * @param bool $preserve Do not minify the source
		 * @return void
		 */
		static public function addStyleFile($file, $preserve=false) {
			self::addStyle(new CSS($file, CSS::TYPE_FILE, !$preserve));
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
				require FORGE_PATH.'/'.$file;
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
		* @param string Glue
		* @return string
		*/
		static public function getScripts($glue) {
			$local = '';
			$elements = [];

			foreach (self::$scripts as $script)
				if ($script->isRemote())
					$elements[] = '<script type="text/javascript" src="'.$script->getFile().'"></script>';
				elseif (!\forge\components\Identity::isDeveloper() && $script->isMinifiable())
					$local .= $script->getSource();
				elseif ($script->isLocal())
					$elements[] = '<script type="text/javascript" src="'.$script->getFile().'"></script>';
				else
					$elements[] = '<script type="text/javascript">'.$script->getSource().'</script>';

			if (strlen($local)) {
				$js = new JavaScript($local);
				$hash = $js->getHash();
				$repo = \forge\components\Files::getCacheRepository();
				try {
					$folder = $repo->getFolder('script');
				}
				catch (\forge\components\Files\exceptions\FileNotFound $e) {
					$folder = $repo->createFolder('script');
				}
				$file = $hash.'.js';
				try {
					$folder->getFile($file);
				}
				catch (\forge\components\Files\exceptions\FileNotFound $e) {
					$folder->createFile($file, \forge\JSMin::minify($local));
				}
				$elements[] = '<script type="text/javascript" src="/cache/script/'.$file.'"></script>';
			}

			return implode($glue, $elements);
		}

		/**
		* Get all CSS elements as string
		* @param string Glue
		* @return string
		*/
		static public function getStyles($glue) {
            $repo = \forge\components\Files::getCacheRepository();
			$elements = [];

            try {
                $folder = $repo->getFolder('style');
            } catch (\forge\components\Files\exceptions\FileNotFound $e) {
                $folder = $repo->createFolder('style');
            }

			foreach (self::$styles as $style)
				if ($style->isRemote())
					$elements[] = '<link href="'.$style->getFile().'" rel="stylesheet" />';
				elseif (!\forge\components\Identity::isDeveloper() && $style->isMinifiable()) {
                    $css = new CSS($style->getSource());
                    $hash = $css->getHash();
                    $file = $hash.'.css';
                    try {
                        $folder->getFile($file);
                    } catch (\forge\components\Files\exceptions\FileNotFound $e) {
                        $folder->createFile($file, \forge\CssMin::minify($style->getSource()));
                    }
                    $elements[] = '<link href="/cache/style/'.$hash.'.css" rel="stylesheet" />';
                }
				elseif ($style->isLocal())
					$elements[] = '<link href="'.$style->getFile().'" rel="stylesheet" />';
				else
					$elements[] = '<style type="text/css" media="screen">'.$style->getSource().'</style>';


			return implode($glue, $elements);
		}

		/**
		 * Get the page title
		 * @return string
		 */
		static public function getTitle() {
			return self::$title;
		}

		/**
		* Get the complete header elements as string
		* @param int Number of tab indentations
		* @return string
		*/
		static public function header($tabs=0) {
			$glue = "\n";
			for ($i=0;$i<$tabs;++$i)
				$glue .= "\t";

			return implode($glue, array_filter(array(
				self::title(),
				self::meta($glue),
				self::getStyles($glue),
				self::getScripts($glue)
			)));
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

			self::addScriptFile('/script/tinymce/tinymce.min.js', true);
			self::addScriptFile('/components/Templates/script/editor.js');

			$id = uniqid();
			$textarea = '<textarea name="'.htmlspecialchars($vars['name']).'" class="editable" id="'.$id.'">'.htmlentities($vars['value']).'</textarea>';

			return $textarea;
		}

		/**
		 * Write an image here which is selected by the client depending on its
		 * pixel density.
		 * @param array $images Array with density => file
		 * @param array $attributes Additional HTML attributes
		 * @return string
		 */
		static public function image($images, $attributes=[]) {
			$script = '<script type="text/javascript">';
			$script .= 'document.write("<img ");';
			$ifs = [];
			ksort($images);
			$images = array_reverse($images, true);
			foreach ($images as $dpr => $image) {
				$out = ['src' => '/'.$image];
				if (empty($attributes['width']) && empty($attributes['height'])) {
					$size = getimagesize(FORGE_PATH.'/'.$image);
					$out['width'] = floor(($dpr == 0 ? 1 : 1/$dpr)*$size[0]);
					$out['height'] = floor(($dpr == 0 ? 1 : 1/$dpr)*$size[1]);
				}
				$if = 'if (window.devicePixelRatio >= '.$dpr.') document.write("';
				foreach ($out as $attribute => $value)
					$if .= $attribute.'=\\"'.htmlentities($value).'\\" ';
				$if .= '");';
				$ifs[] = $if;
			}
			$script .= implode(' else ', $ifs);
			$script .= 'document.write("';
			if (count($attributes))
				foreach ($attributes as $attribute => $value)
					$script .= $attribute.'=\\"'.htmlentities($value).'\\" ';
			$script .= '/>");</script>';

			return $script;
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
		* @param string Glue
		* @return string
		*/
		static public function meta($glue) {
			if (!is_array($meta = self::getMeta()))
				return null;

			$output = [];
			foreach ($meta as $key => $value)
				$output[] = '<meta name="'.self::html($key).'" content="'.self::html($value).'" />';

			return implode($glue, $output);
		}

		/**
		 * Handle any response from a specific controller
		 * @param string|array $controller
		 * @return string
		 */
		static public function response($controller) {
			$html = null;

			if (is_array($controller)) {
				foreach ($controller as $item)
					$html .= self::response($item);

				return $html;
			}

			if (\forge\Controller::getController() == $controller && \forge\Controller::getCode() != \forge\Controller::RESULT_PENDING) {
				self::addStyleFile('/css/controller.css');
			    $class = \forge\Controller::getCode() == \forge\Controller::RESULT_OK ? 'success' : 'error';
				$html = '<p class="controller '.$class.'">'.self::html(\forge\Controller::getMessage()).'</p>';
			}

			return $html;
		}

		/**
		 * Load require.js and all available plugins
		 */
		static public function requireJS() {
			self::addScriptFile('/vendor/requirejs/require.js', true);

			$plugins = [
				'adminlte' => ['/templates/forge-admin/script/adminlte.min', ['jquery']],
				'bootstrap' => ['https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min', ['jquery']],
				'cookie' => 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min',
				'datatables.bootstrap' => '/vendor/datatables.net/datatables.bs.min',
				'datatables.net' => '/vendor/datatables.net/datatables.min',
				'datatables.rowReorder' => '/vendor/datatables.net/rowreorder.min',
				'domReady' => '/vendor/domReady/domReady',
				'forge' => '/script/forge',
				'icheck' => ['/templates/forge-admin/script/icheck.min', ['jquery']],
				'jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min'
			];
			foreach (\forge\Addon::getAddons(true) as $addon)
				if (in_array('forge\components\Templates\RequireJS', class_implements($addon)))
					$plugins = array_merge($plugins, $addon::getRequireJS());
			ksort($plugins);

			self::addScript(self::display('components/Templates/tpl/js.requirejs.php', ['plugins' => $plugins]));
		}

		static public function select($name, $options, $value=null, $auto=true, $attr=[]) {
			$attr['name'] = $name;

			$default = self::getRequestField($name, $value);

			return self::display(
				'components/Templates/tpl/inc.select.php',
				[
					'attributes' => $attr,
					'default' => $default,
					'options' => $options
				]
			);
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
		 * Set the page title
		 * @param string $title
		 */
		static public function setTitle($title) {
			self::$title = $title;
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

		/**
		 * Get the title element
		 * @return string
		 */
		static public function title() {
			return '<title>'.self::html(self::getTitle()).'</title>';
		}
	}
