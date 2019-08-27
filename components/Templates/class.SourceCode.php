<?php
	/**
	* class.SourceCode.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\components\Templates;
	
	/**
	 * A representation of a JavaScript or CSS source file
	 */
	abstract class SourceCode {
		/**
		 * The script is a file and should be determined wether it's local or not
		 */
		const TYPE_FILE = 8;
		
		/**
		 * The script is a local file
		 */
		const TYPE_LOCAL = 2;
		
		/**
		 * The script is a remote file
		 */
		const TYPE_REMOTE = 4;
		
		/**
		 * The script is pure source code
		 */
		const TYPE_SOURCE = 1;
		
		/**
		 * URL to the source file
		 * @var string Source file URL
		 */
		private $file = false;
		
		/**
		 * Allow minification?
		 * @var bool
		 */
		private $minify;
		
		/**
		 * Source code
		 * @var string Source code
		 */
		private $source = false;
		
		/**
		 * Script type
		 * @var int Script type
		 */
		private $type;
		
		/**
		 * Create a JavaScript object from a file
		 * @param string $script Either URL to a file or the source code
		 * @param int $type What type of argument $script has
		 * @param bool $minify Allow minification of the source
		 */
		public function __construct($source, $type=self::TYPE_SOURCE, $minify=true) {
			if ($type & self::TYPE_FILE) {
				$this->type = $source[0] === '/' && $source[1] !== '/' ?
					self::TYPE_LOCAL : self::TYPE_REMOTE;
			}
			else
				$this->type = $type;
			
			switch ($this->type) {
				case self::TYPE_LOCAL:
					$this->file = $source;
					$this->source = file_get_contents(FORGE_PATH.$this->file);
					break;
				
				case self::TYPE_REMOTE:
					$this->file = $source;
					break;
				
				case self::TYPE_SOURCE:
					$this->source = (string)$source;
					break;
			}
			
			$this->minify = (bool)$minify;
		}
		
		/**
		 * Get the source code
		 * @return string
		 */
		public function __toString() {
			return $this->getSource();
		}
		
		/**
		 * Get the source hash
		 * @return string
		 */
		public function getHash() {
			return md5($this->source);
		}
		
		/**
		 * Get the file path
		 * @return string
		 */
		public function getFile() {
			return $this->file;
		}
		
		/**
		 * Get the source code
		 * @return string
		 */
		public function getSource() {
			return $this->source;
		}
		
		/**
		 * Is the script a local file?
		 * @return bool
		 */
		public function isLocal() {
			return $this->type & self::TYPE_LOCAL;
		}
		
		/**
		 * Allow minification?
		 * @return bool
		 */
		public function isMinifiable() {
			return $this->minify;
		}
		
		/**
		 * Is the script a remote file?
		 * @return bool
		 */
		public function isRemote() {
			return $this->type & self::TYPE_REMOTE;
		}
		
		/**
		 * Is the script pure source code?
		 * @return bool
		 */
		public function isSource() {
			return $this->type & self::TYPE_SOURCE;
		}
	}