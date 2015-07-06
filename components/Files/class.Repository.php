<?php
	/**
	* class.Repository.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	/**
	* Represents a set of files related to one another through a directory set
	*/
	class Repository {
		/**
		 * Root element
		 * @var db\TreeNode
		 */
		private $root;
		
		/**
		 * Initiate an existing repository
		 * @param int $id
		 */
		public function __construct($id=null) {
			if ($id !== null)
				$this->loadNode(new db\TreeNode($id));
		}
		
		public function addSize($size) {
			$this->root->size += (int)$size;
			$this->root->save();
			
			if ($this->root->parent != 0)
				$this->root->parent->addSize($size);
		}
		
		/**
		 * Create a new file in the repository
		 * @param string Path and file name
		 * @param string File contents
		 * @return Files\db\TreeNode
		 */
		public function createFile($filename, $content) {
			// Todo: Add folders
			//$folders = explode('/', substr($target, strlen(FORGE_PATH)));
			//array_pop($folders);
			
			// Generate a hash position
			$sha1 = sha1($content);
			$path = FORGE_PATH.'/files/'.substr($sha1, 0, 2);
			$target = $path.'/'.substr($sha1, 2);
			
			$blob = new db\Blob;
			$blob->hash = $sha1;
			try {
				$blob->select('hash');
			} catch (\Exception $e) {
				$blob->size = strlen($content);
				$blob->insert();
			}
			
			if (!file_exists($target)) {
				if (!file_exists($path) && !mkdir($path))
					throw new \Exception('Could not create a new folder');
				elseif (!is_dir($path))
					throw new \Exception('A file unexpectedly exists in /files/');

				if (!touch($target))
					throw new \Exception('Could not create a new file');

				if (file_put_contents($target, $content) === false)
					throw new \Exception('Could not write to the new file');
			}
			
			$node = new db\TreeNode;
			$node->parent = $this->root;
			$node->name = $filename;
			$node->size = $blob->size;
			$node->blob = $blob;
			$node->insert();
			
			return $node;
		}
		
		/**
		 * Create a new folder
		 * @param string $name Name of the folder
		 * @return \forge\components\Files\db\TreeNode
		 */
		public function createFolder($name) {
			$node = new db\TreeNode;
			$node->parent = $this->root;
			$node->name = $name;
			$node->insert();
			
			return Folder::newFromNode($node);
		}
		
		/**
		 * Create a new tree
		 * @return \forge\components\Files\db\TreeNode
		 */
		static public function createRepository() {
			$tree = new db\TreeNode;
			$tree->insert();
			
			return $tree;
		}
		
		public function deleteFile($filename) {
			$node = new db\TreeNode;
			$node->parent = $this->root;
			$node->name = $filename;
			$node->select(['parent', 'name']);
			
			$blob = $node->blob;
			
			$node->delete();
			
			$search = new db\TreeNode;
			$search->blob = $blob;
			try {
				$search->select('blob');
			} catch (\forge\components\Databases\exceptions\NoData $e) {
				$file = new File($blob);
				$size = $file->delete();
				
				$this->addSize(-$size);
			}
		}
		
		public function getChildren($models=false) {
			$children = new \forge\components\Databases\TableList([
				'type' => new db\TreeNode,
				'where' => [
					'parent' => $this->root->getId()
				],
				'order' => [
					'blob' => 'ASC',
					'name' => 'ASC'
				]
			]);
			
			if ($models)
				return $children;
			
			$repos = [];
			foreach ($children as $child) {
				$repos[] = Folder::newFromNode($child);
			}
			
			return $repos;
		}
		
		/**
		 * Get a particular file relative to this path
		 * @param string $path Path to the requested file
		 * @return \forge\components\Files\PhysicalFile
		 */
		public function getFile($path) {
			try {
				$node = $this->getNode($path);
			}
			catch (\forge\components\Databases\exceptions\NoData $e) {
				throw new exceptions\FileNotFound;
			}
			
			if ($node === null)
				throw new exceptions\FileNotFound;
			
			return new File($node->blob, $node);
		}
		
		/**
		 * Get a particular folder relative to this path
		 * @param string $path Path to the requested folder
		 * @return \forge\components\Files\Repository
		 */
		public function getFolder($path) {
			try {
				$node = $this->getNode($path);
			}
			catch (\forge\components\Databases\exceptions\NoData $e) {
				throw new exceptions\FileNotFound;
			}
			
			if ($node === null)
				throw new exceptions\FileNotFound;
			
			return Folder::newFromNode($node);
		}
		
		/**
		 * Get a node
		 * @param type $path
		 * @return \forge\components\Files\TreeNode
		 */
		private function getNode($path) {
			if (strlen($path) == 0)
				return $this->root;
			
			$folders = explode('/', $path);
			$parent = $this->root;
			
			for ($i=0;$i<count($folders);++$i) {
				$node = new db\TreeNode();
				$node->parent = $parent;
				$node->name = $folders[$i];
				$parent = $node->select(['parent', 'name']);
			}
			
			return $parent;
		}
		
		/**
		 * Get the size of the repository
		 * @return int
		 */
		public function getSize() {
			return $this->root->size;
		}
		
		/**
		 * Load a node
		 * @param \forge\components\Files\db\TreeNode $node
		 */
		public function loadNode(db\TreeNode $node) {
			$this->root = $node;
		}
		
		/**
		 * Create a new repository from an already loaded node
		 * @param \forge\components\Files\db\TreeNode $node
		 * @return \forge\components\Files\db\TreeNode
		 */
		static public function newFromNode(db\TreeNode $node) {
			$repo = new self;
			$repo->loadNode($node);
			
			return $repo;
		}
		
		public function rename($name) {
			$this->root->name = $name;
			$this->root->save();
		}

		/**
		 * Upload a file to a folder
		 * @param array $file $_FILE entry of the file
		 * @return File
		 * @throws \Exception
		 */
		public function uploadFile($file) {
			// The uploaded file should exist
			if (!is_uploaded_file($file['tmp_name']))
				throw new exceptions\FileNotFound;
			
			// Does the file already exist?
			$child = new db\TreeNode();
			$child->parent = $this->root;
			$child->name = $file['name'];
			try {
				$child->select(['parent', 'name']);
				
				throw new exceptions\AlreadyExists;
			} catch (\forge\components\Databases\exceptions\NoData $e) {}
			
			// Get the hash of the file and find out its path components
			$hash = sha1_file($file['tmp_name']);
			$dirname = FORGE_PATH.'/files/'.substr($hash, 0, 2);
			$filename = $dirname.'/'.substr($hash, 2);

			// Move the uploaded file into the target
			if (!file_exists($filename)) {
				if (!file_exists($dirname))
					mkdir($dirname);
				move_uploaded_file($file['tmp_name'], $filename);
				
				try {
					$blob = new db\Blob;
					$blob->hash = $hash;
					$blob->size = filesize($filename);
					$blob->insert();
				}
				catch (\Exception $e) {
					unlink($filename);
					
					throw new \Exception('Could not insert blob into database', 0, $e);
				}
			}
			else {
				try {
					$blob = new db\Blob;
					$blob->hash = $hash;
					$blob->size = filesize($filename);
					$blob->select(['hash']);
				}
				catch (\forge\components\Databases\exceptions\NoData $e) {
					try {
						$blob->insert();
					}
					catch (\Exception $e) {
						unlink($filename);

						throw new \Exception('Could not insert blob into database', 0, $e);
					}
				}
			}
			$node = new db\TreeNode;
			$node->name = $file['name'];
			$node->blob = $blob;
			$node->parent = $this->root;
			$node->size = $blob->size;
			$node->insert();
			
			// Update the folder sizes
			$this->addSize($node->size);

			// Return a File object for the result
			return new File($blob, $node);
		}
	}