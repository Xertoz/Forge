<?php
	/**
	* class.Admin.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	/**
	* File component for Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function index() {
			\forge\components\Accounts::restrict('Files','admin','use','r');

			$path = 'files/'.(empty($_REQUEST['path']) ? null : $_REQUEST['path'].'/').'*';
			$path = preg_replace('/(\.){2,}/', '', $path);
			$files = $folders = array();
			foreach (glob($path) as $file)
				if ($file != '..') {
					if (is_dir($file))
						$target = &$folders;
					else
						$target = &$files;

					$target[] = array(
						'date' => date('Y-m-d H:i:s',filectime($file)),
						'name' => substr($file,strlen($path)-1),
						'size' => filesize($file),
						'type' => filetype($file)
					);
				}
			$matrix = new \forge\components\XML\ArrayMatrix(array_merge($folders, $files),array('name','dir'));

			return \forge\components\Templates::display(
				'components/Files/tpl/acp.files.php',
				array(
					'matrix' => $matrix
				)
			);
		}
	}