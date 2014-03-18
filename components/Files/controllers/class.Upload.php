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
			
			if (!isset($_POST['path']))
				throw new \forge\HttpException(_('A path must be chosen'),
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			try {
				\forge\components\Files\File::upload($_FILES['file'], $_POST['path']);
			}
			catch (\Exception $e) {
				throw new \forge\HttpException(_('The file could not be uploaded!'),
						\forge\HttpException::HTTP_CONFLICT);
			}
			
			self::setResponse(_('The file was successfully uploaded!'), self::RESULT_OK);
		}
	}