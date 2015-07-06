<?php
	/**
	* page.CacheRequest.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	/**
	* The view that accounts for file requests.
	*/
	class CacheRequest extends \forge\RequestHandler {
		public function handle() {
			try {
				$repo = \forge\components\Files::getCacheRepository();
				$file = $repo->getFile($this->getPathDecoded());
				$file->passthru();
			}
			catch (\forge\components\Files\exceptions\FileNotFound $e) {
				throw new \forge\HttpException('File not found', \forge\HttpException::HTTP_NOT_FOUND, $e);
			}

			return (string)null;
		}
	}