<?php
	/**
	* class.ThumbnailRequest.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	use forge\HttpException;

	/**
	* The thumbnail view
	*/
	class ThumbnailRequest extends \forge\RequestHandler {
		/**
		 * Create a thumbnail and output it to the client
		 * @throws \forge\HttpException
		 * @throws \Exception
		 */
		public function handle() {
			$parts = explode('/', $this->getPathDecoded());
			
			if (count($parts) < 3)
				throw new \forge\HttpException('Invalid URL',
					\forge\HttpException::HTTP_BAD_REQUEST);
			
			$width = array_shift($parts);
			$height = array_shift($parts);
			$name = implode('/', $parts);
			
			try {
				$file = new \forge\components\Files\File($name);
				
				if (!$file->isFile())
					throw new \Exception('Not requesting a file');
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('File does not exist',
					\forge\HttpException::HTTP_NOT_FOUND);
			}
			
			$finfo = new \finfo(\FILEINFO_MIME_TYPE);
			$thumb = new Thumbnail($file->getRealPath());
			$this->setContentType($finfo->file($parsed = $thumb->Get(
				$width,
				$height,
				isset($_GET['dimension']) ? (int)$_GET['dimension'] : Thumbnail::DIMENSION_STATIC)));
			try {
				echo new File(substr($parsed, strlen(FORGE_PATH.'/files/')));
			}
			catch (\Exception $e) {
				throw new HttpException('Source image not found', HttpException::HTTP_NOT_FOUND);
			}
		}
	}