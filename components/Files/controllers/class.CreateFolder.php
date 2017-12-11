<?php
	/**
	* class.CreateFolder.php
	* Copyright 2017 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\controllers;

	/**
	* Attempt a login to the system
	*/
	class CreateFolder extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Files.Admin');
			
			$name = \forge\Post::getString('name');
			if ($name === null)
				throw new \forge\HttpException('A folder name must be chosen',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			$path = \forge\Post::getString('path');
			if ($path === null)
				throw new \forge\HttpException('A path must be chosen',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			try {
				$repo = \forge\components\Files::getFilesRepository();
				$folder = $repo->getFolder($path);
				$folder->createFolder($name);
			}
			catch (\Exception $e) {echo $e->getMessage();
				throw new \forge\HttpException('The folder could not be created!',
						\forge\HttpException::HTTP_CONFLICT);
			}
			
			self::setResponse(self::l('The folder was created!'), self::RESULT_OK);
		}
	}