<?php
	/**
	* class.Admin.php
	* Copyright 2010-2013 Mattias Lindholm
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
		static public function index() {
			\forge\components\Identity::restrict('com.Files.Admin');

			$repo = \forge\components\Files::getFilesRepository();
			$dir = $repo->getFolder(\forge\Get::getString('path', ''));
			$children = $dir->getChildren(true);
			$array = [];
			foreach ($children as $child) {
				$array[] = [
					'date' => $child->created,
					'name' => $child->name,
					'size' => $child->size,
					'type' => $child->blob ? 'file' : 'dir'
				];
			}
			$matrix = new \forge\components\XML\ArrayMatrix($array, ['name','dir']);
			
			return \forge\components\Templates::display(
				'components/Files/tpl/acp.files.php',
				array(
					'matrix' => $matrix
				)
			);
		}
	}