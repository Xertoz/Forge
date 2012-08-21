<?php
	/**
	* class.File.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	/**
	 * A helper class for managing files on the virtual disk
	 */
	class File {
		/**
		 * Directory type
		 */
		const TYPE_DIR = 2;
		
		/**
		 * File type
		 */
		const TYPE_FILE = 1;
		
		/**
		 * File contents
		 * @var string
		 */
		private $content;
		
		/**
		 * File location
		 * @var string
		 */
		private $location;
		
		/**
		 * Flag for content read status
		 * @var bool
		 */
		private $read = false;
		
		/**
		 * Flag for file type
		 * @var int
		 */
		private $type;
		
		/**
		 * Open an existing file
		 * @param string $location File path and name
		 * @throws \Exception
		 */
		public function __construct($location) {
			$this->location = self::jail($location);
			
			if (!file_exists($this->location))
				throw new \Exception(_('File not found'));
			
			$this->type = is_file($this->location) ? self::TYPE_FILE : self::TYPE_DIR;
		}
		
		/**
		 * Get the file contents
		 * @return string
		 */
		public function __toString() {
			if ($this->read)
				return;
			$this->read = true;
			
			$this->content = file_get_contents($this->location);
			
			return $this->content;
		}
		
		/**
		 * Create a new file
		 * @param string $name File path and name
		 * @param int $type File type
		 * @return \forge\components\Files\File
		 * @throws \Exception
		 */
		static public function create($name, $type=self::TYPE_FILE) {
			$target = self::jail($name);
			
			$folders = explode('/', $target);
			array_pop($folders);
			$path = '';
			foreach ($folders as $folder)
				if (!file_exists($path.=$folder.'/'))
					mkdir($path);
			
			if (file_exists($target))
				return new File($name);
			
			switch ($type) {
				case self::TYPE_DIR:
					mkdir($target);
					break;
				
				case self::TYPE_FILE:
					touch($target);
					break;
				
				default:
					throw new \Exception(_('Unknown file type'));
			}
			
			try {
				return new File($name);
			}
			catch (\Exception $e) {
				throw new \Exception(_('File could not be created'));
			}
		}
		
		/**
		 * Delete the file
		 */
		public function delete() {
			if ($this->type & self::TYPE_DIR) {
				$delete = function($path) {
					foreach (glob($path.'/*') as $file)
						if (is_dir($file))
							$delete($file);
						else
							unlink($file);
					
					rmdir($path);
				};
				
				$delete($this->location);
			}
			else
				unlink($this->location);
		}
		
		/**
		 * Is this a directory?
		 * @return bool
		 */
		public function isDirectory() {
			return $this->type | self::TYPE_DIR;
		}
		
		/**
		 * Is this a file?
		 * @return bool
		 */
		public function isFile() {
			return $this->type | self::TYPE_FILE;
		}
		
		/**
		 * Find the file in the virtual disk
		 * @param string $name
		 * @return string
		 */
		static private function jail($name) {
			$parts = explode('/', $name);
			
			foreach ($parts as $key => $item)
				if ($item == '..')
					unset($parts[$key]);
			
			return 'files/'.implode('/', $parts);
		}
		
		/**
		 * Rename the file
		 * @param string $to
		 */
		public function rename($to) {
			$new = self::jail($to);
			rename($this->location, $new);
			$this->location = $new;
		}
		
		/**
		 * Set the content
		 * @param string $content
		 * @param bool $save Write directly to disk?
		 */
		public function set($content, $save=false) {
			$this->read = true;
			$this->content = (string)$content;
			
			if ($save)
				$this->save();
		}
		
		/**
		 * Save the file content
		 */
		public function save() {
			file_put_contents($this->location, $this->content);
		}
	}