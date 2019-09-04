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
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function __construct($id=null) {
			if ($id !== null)
				$this->loadNode(new db\TreeNode($id));
		}

		public function addSize($size) {
			$this->root->size += (int)$size;
			$this->root->save();

			if ($this->root->parent instanceof db\TreeNode) {
				$parent = new Repository($this->root->parent);
				$parent->addSize($size);
			}
		}

		/**
		 * Create a new file in the repository
		 * @param $filename
		 * @param string $content
		 * @return db\TreeNode
		 * @throws \forge\components\Databases\exceptions\NoData
		 */
		public function createFile($filename, $content='') {
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

			$this->addSize($blob->size);

			return $node;
		}

		/**
		 * Create a new folder
		 * @param string $name Name of the folder
		 * @return Repository
		 * @throws \forge\components\Databases\exceptions\NoData
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
		 * @throws \forge\components\Databases\exceptions\NoData
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
				try {
					$file = new File($blob);
					$size = $file->delete();
				} catch (\Exception $e) {}

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

		public function get($path) {
			try {
				$node = $this->getNode($path);
			}
			catch (\forge\components\Databases\exceptions\NoData $e) {
				throw new exceptions\FileNotFound;
			}

			if ($node === null)
				throw new exceptions\FileNotFound;

			return $node->blob === null ? Folder::newFromNode($node) : new File($node->blob, $node);
		}

		/**
		 * Get a particular file relative to this path
		 * @param string $path Path to the requested file
		 * @return File
		 * @throws exceptions\FileNotFound
		 */
		public function getFile($path='') {
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
		 * @return Repository
		 * @throws \forge\components\Databases\exceptions\NoData
		 * @throws exceptions\FileNotFound
		 */
		public function getFolder($path='') {
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

		public function getId() {
			return $this->root->getId();
		}

		public function getHREF() {
			return $this->root->link->page_url;
		}

		public function getName() {
			return is_null($this->root->link) ? $this->root->name : $this->root->link->page_title;
		}

		/**
		 * Get a node
		 * @param type $path
		 * @return \forge\components\Databases\Table|db\TreeNode
		 * @throws \forge\components\Databases\exceptions\NoData
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

		static public function loadLink($id) {
			$node = new db\TreeNode();
			$node->link = $id;
			$node->select('link');

			return new Repository($node->getId());
		}

		public function isFile() {
			return !self::isFolder();
		}

		public function isFolder() {
			return $this->root->blob === null;
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
		 * @return Repository
		 * @throws \forge\components\Databases\exceptions\NoData
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
		 * Upload multiple files to a folder
		 * @param array $files $_FILE entry of the file
		 * @return array
		 * @throws \forge\components\Databases\exceptions\NoData
		 * @throws exceptions\FileNotFound
		 */
		public function uploadFiles($files) {
			// The uploaded files should exist
			foreach ($files['tmp_name'] as $tmp_name)
				if (!is_uploaded_file($tmp_name))
					throw new exceptions\FileNotFound;

			$child = new db\TreeNode();
			$child->parent = $this->root;

			$return = [];
			for ($i=0;$i<count($files['tmp_name']);++$i) {
				// Find a new name if the parent already exists
				$j = -1;
				$name = pathinfo($files['name'][$i], \PATHINFO_FILENAME);
				$ext = pathinfo($files['name'][$i], \PATHINFO_EXTENSION);
				do {
					$exists = false;
					$child->name = ++$j > 0 ? $name.' ('.$j.')' : $name;
					if ($ext)
						$child->name .= '.'.$ext;

					try {
						$child->select(['parent', 'name']);
						$exists = true;
					} catch (\forge\components\Databases\exceptions\NoData $e) {}
				} while ($exists);

				// Get the hash of the file and find out its path components
				$hash = sha1_file($files['tmp_name'][$i]);
				$dirname = FORGE_PATH.'/files/'.substr($hash, 0, 2);
				$filename = $dirname.'/'.substr($hash, 2);

				// Move the uploaded file into the target
				if (!file_exists($filename)) {
					if (!file_exists($dirname))
						mkdir($dirname);
					move_uploaded_file($files['tmp_name'][$i], $filename);

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
						$blob->select(['hash']);
					}
					catch (\forge\components\Databases\exceptions\NoData $e) {
						try {
							$blob->size = filesize($filename);
							$blob->insert();
						}
						catch (\Exception $e) {
							throw new \Exception('Could not insert blob into database', 0, $e);
						}
					}
				}

				// Create the virtual file
				$node = new db\TreeNode;
				$node->name = $child->name;
				$node->blob = $blob;
				$node->parent = $this->root;
				$node->size = $blob->size;
				$node->insert();

				// Update the folder sizes
				$this->addSize($node->size);

				$return[] = new File($blob, $node);
			}

			// Return a File object for the result
			return $return;
		}
	}
