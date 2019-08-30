<?php
	/**
	* class.Admin.php
	* Copyright 2010-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	use forge\components\Templates;
	use forge\HttpException;

	/**
	* File component for Forge
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function edit() {
			$node = new db\TreeNode(\forge\Get::getInt('id'));
			$blob = new db\Blob($node->blob);

			return \forge\components\Templates::display(
				'components/Files/tpl/acp.edit.php',
				[
					'node' => $node,
					'blob' => $blob
				]
			);
		}

		static public function file() {
			\forge\components\Identity::restrict('com.Files.Admin');

			try {
				$node = new db\TreeNode(\forge\Get::getInt('id'));
			}
			catch (\Exception $e) {
				throw new HttpException('File not found', HttpException::HTTP_NOT_FOUND);
			}

			$parents = [];
			$current = $node;

			while ($current->parent !== null) {
				$current = new db\TreeNode($current->parent);
				$parents[] = $current;
			}

			$repo = new Repository(end($parents)->getId());

			return Templates::display('components/Files/tpl/adm.file.php', [
				'node' => $node,
				'parents' => $parents,
				'repo' => $repo
			]);
		}

		static public function index() {
			\forge\components\Identity::restrict('com.Files.Admin');

			$nodes = new \forge\components\Databases\TableList([
				'type' => new \forge\components\Files\db\TreeNode,
				'where' => ['null:parent' => null]
			]);

			$repos = [];
			$size = 0;
			foreach ($nodes as $node) {
				$repos[] = Repository::newFromNode($node);
				$size += $repos[count($repos)-1]->getSize();
			}

			return \forge\components\Templates::display('components/Files/tpl/adm.index.php', [
				'repos' => $repos,
				'size' => $size
			]);
		}

		static public function repo() {
			\forge\components\Identity::restrict('com.Files.Admin');

			$repo = new Repository(\forge\Get::getInt('id'));
			$dir = $repo->getFolder($path = \forge\Get::getString('path', ''));
			$children = $dir->getChildren(true);
			$table = new \forge\components\Templates\DataTable($children);

			return \forge\components\Templates::display(
				'components/Files/tpl/adm.repo.php',
				array(
					'path' => $path,
					'table' => $table,
					'repo' => $repo
				)
			);
		}
	}
