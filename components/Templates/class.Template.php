<?php
	/**
	* class.Template.php
	* Copyright 2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;

	/**
	* Template information
	*/
	class Template {
		/**
		 * @var string Name of the author(s)
		 */
		private $author = null;
		
		/**
		 * @var string Copyright disclaimer
		 */
		private $copyright = null;
		
		/**
		 * @var array|string List of supported modules
		 */
		private $modules = [];
		
		/**
		 * @var string Human name of the template
		 */
		private $name = null;
		
		/**
		 * @var string Root path of the template
		 */
		private $path;
		
		/**
		 * @var bool Wether or not the admin can select this template
		 */
		private $selectable;
		
		public function __construct($template) {
			$this->path = FORGE_PATH.'/templates/'.$template;
			
			if (!file_exists($file = $this->path.'/template.xml'))
				throw new \Exception(_('File template.xml not found'));
			$xml = simplexml_load_file($file);
			
			$this->author = (string)$xml->author;
			$this->copyright = (string)$xml->copyright;
			$this->name = (string)$xml->name;
			$this->selectable = isset($xml->selectable) && (string)$xml->selectable == "false" ? false : true;

			foreach ($xml->modules as $element) 
				$this->modules[] = (string)$element->module;
		}
		
		public function getAuthor() {
			return $this->author;
		}
		
		public function getCopyright() {
			return $this->copyright;
		}
		
		public function getModules() {
			return $this->modules;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function isSelectable() {
			return $this->selectable;
		}
	}