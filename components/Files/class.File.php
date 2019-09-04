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
		 * @param db\Blob $blob
		 * @param db\TreeNode|null $node
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
			$modified = filemtime($this->path);
			
			\forge\RequestHandler::setContentType(MimeType::fromExtension($this->node->name));
			\forge\RequestHandler::setContentLength(filesize($this->path));
			\forge\RequestHandler::setLastModified($modified);
			\forge\RequestHandler::setETag($this->blob->hash);
			
			if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
					&& strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modified)
				header('HTTP/1.1 304 Not Modified');
			else {
				header('HTTP/1.1 200 OK', true);
				readfile($this->path);
			}
		}
	}