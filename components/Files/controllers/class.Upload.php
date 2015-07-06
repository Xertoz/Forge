<?php
	/**
	* class.Upload.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\controllers;

	/**
	* Upload a file to forge
	*/
	class Upload extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Files.Admin');
			
			$path = \forge\Post::getString('path');
			if ($path === null)
				throw new \forge\HttpException('A path must be chosen',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			try {
				$repo = \forge\components\Files::getFilesRepository();
				$folder = $repo->getFolder($path);
				$folder->uploadFile($_FILES['file']);
			}
			catch (\Exception $e) {echo $e->getMessage();
				throw new \forge\HttpException('The file could not be uploaded!',
						\forge\HttpException::HTTP_CONFLICT);
			}
			
			self::setResponse(self::l('The file was successfully uploaded!'), self::RESULT_OK);
		}
	}