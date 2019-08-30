<?php
	/**
	* class.Rename.php
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
	class Rename extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Files.Admin');
			
			/*$source = \forge\Post::getString('source');
			if ($source === null)
				throw new \forge\HttpException('A source file must be chosen',
						\forge\HttpException::HTTP_BAD_REQUEST);
			$source = explode('/', $source);
			$file = array_pop($source);
			$dir = implode('/', $source);
			
			$target = \forge\Post::getString('target');
			if ($target === null)
				throw new \forge\HttpException('A new file name must be chosen',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			try {
				$repo = \forge\components\Files::getFilesRepository();
				$folder = $repo->getFolder($dir);
				$folder->getFolder($file)->rename($target);
			}
			catch (\Exception $e) {echo $e->getMessage();
				throw new \forge\HttpException('The file does not exist!',
						\forge\HttpException::HTTP_CONFLICT);
			}
			
			self::setResponse(self::l('The file was successfully renamed!'), self::RESULT_OK);*/
		}
	}