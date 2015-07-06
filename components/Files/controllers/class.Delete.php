<?php
	/**
	* class.Delete.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\controllers;

	/**
	* Attempt a login to the system
	*/
	class Delete extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Files.Admin');
			
			$path = \forge\Post::getString('file');
			if ($path === null)
				throw new \forge\HttpException('A file must be chosen',
						\forge\HttpException::HTTP_BAD_REQUEST);
			$path = explode('/', $path);
			$file = array_pop($path);
			$dir = implode('/', $path);
			
			$repo = \forge\components\Files::getFilesRepository();
			$folder = $repo->getFolder($dir);
			
			try {
				$folder->deleteFile($file);
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('The file could not be deleted',
						\forge\HttpException::HTTP_CONFLICT);
			}
			
			self::setResponse(self::l('The file was successfully deleted!'), self::RESULT_OK);
		}
	}