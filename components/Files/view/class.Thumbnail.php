<?php
	/**
	* view.Thumbnail.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\views;

	/**
	* The thumbnail view
	*/
	class Thumbnail {
		/**
		* Content-Type header
		* @var string
		*/
		private $contentType;

		/**
		* Initialize the request
		*/
		public function __construct($options) {
			// Find out what file needs resizing and to what dimensions
			try {
				preg_match('/^(\d+)\/(\d+)\/(.*).(jpg|png|gif)$/D',$options['url'],$matches);
				list($width,$height,$file,$ext) = array_splice($matches,1);
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('Unknown request',\forge\HttpException::HTTP_NOT_FOUND);
			}

			// Secure the path
			$path = $file.'.'.$ext;
			\forge\components\Files::securePath($path);

			// Get the thumbnail object
			$thumb = new \forge\components\Files\Thumbnail($path);

			// Set content type
			switch ($ext) {
				case 'png':
					$this->contentType = 'image/png';
				break;

				case 'jpg':
					$this->contentType = 'image/jpeg';
				break;

				case 'gif':
					$this->contentType = 'image/gif';
				break;
			}

			$this->content = file_get_contents($thumb->get($width,$height));
		}

		/**
		* Output the image
		* @return string
		*/
		public function __toString() {
			header('Content-Type: '.$this->contentType);

			return parent::__toString();
		}
	}
?>