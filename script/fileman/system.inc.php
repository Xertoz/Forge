<?php
	require_once __DIR__.'/../../forge.php';
	
	\forge\components\Files\File::create('upload', \forge\components\Files\File::TYPE_DIR);
	
	define('BASE_PATH', __DIR__);
	define('ROOT_PATH', FORGE_PATH.'/files/upload');