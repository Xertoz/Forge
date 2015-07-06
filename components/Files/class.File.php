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
	 * A file within a repository
	 */
	class File {
		/**
		 * Blob
		 * @var db\Blob
		 */
		private $blob;
		
		/**
		 * TreeNode
		 * @var db\TreeNode
		 */
		private $node;
		
		/**
		 * Path to the physical file
		 * @var string
		 */
		private $path;
		
		/**
		 * Open an existing file
		 * @param int db\Blob Blob
		 * @throws \Exception
		 */
		public function __construct(db\Blob $blob, db\TreeNode $node=null) {
			$this->blob = $blob;
			$folder = substr($blob->hash, 0, 2);
			$file = substr($blob->hash, 2);
			$this->path = FORGE_PATH.'/files/'.$folder.'/'.$file;
			$this->node = $node;
		}
		
		public function delete() {
			$size = $this->blob->size;
			$folder = substr($this->blob->hash, 0, 2);
			$file = substr($this->blob->hash, 2);
			unlink(FORGE_PATH.'/files/'.$folder.'/'.$file);
			$this->blob->delete();
			
			return $size;
		}
		
		public function passthru() {
			header('Content-type: '.MimeType::fromExtension($this->node->name));
			header('Content-length: '.filesize($this->path));

			if (($fh = fopen($this->path,'rb')) !== false) {
				fpassthru($fh);
				fclose($fh);
			}
			else
				throw new exceptions\FileNotFound;
		}
	}