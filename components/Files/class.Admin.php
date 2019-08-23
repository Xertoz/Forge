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
			$dir = $repo->getFolder(\forge\Get::getString('path', ''));
			$children = $dir->getChildren(true);
			$array = [];
			foreach ($children as $child) {
				$array[] = [
					'id' => $child->getId(),
					'date' => $child->created,
					'name' => $child->name,
					'size' => $child->size,
					'type' => $child->blob ? 'file' : 'dir'
				];
			}
			$matrix = new \forge\components\XML\ArrayMatrix($array, ['name','dir']);
			
			return \forge\components\Templates::display(
				'components/Files/tpl/adm.repo.php',
				array(
					'matrix' => $matrix,
					'repo' => $repo
				)
			);
		}
	}