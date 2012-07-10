<?php
	/**
	* class.Thumbnail.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files;

	/**
	* Thumbnail creating class
	* @version 1.0.2
	*/
	class Thumbnail {
		/**
		* What a tru color image require
		*/
		const TrueColorMemory = 7472;

		/**
		* Don't use other pixels for thumbnail
		*/
		const DIMENSION_STATIC = 1;

		/**
		* Don't resize width
		*/
		const DIMENSION_ANCHOR_WIDTH = 2;

		/**
		* Don'r resize height
		*/
		const DIMENSION_ANCHOR_HEIGHT = 3;

		/**
		* Don't go past anything
		*/
		const DIMENSION_MAXIMUM = 4;

		/**
		* Don't go below anything
		*/
		const DIMENSION_MINIMUM = 5;

		/**
		* Resize traditionally (no rules)
		*/
		const RESIZE_FULLY = 1;

		/**
		* Disallow bigger images
		*/
		const RESIZE_NO_ENLARGEMENT = 2;

		/**
		* Pixel byte allocation
		*/
		const PixelBytes = 2;

		/**
		* True color pixel byte allocation
		*/
		const TrueColorBytes = 4;

		/**
		* Source image resource
		*/
		private $SrcImage;

		/**
		* Source image path
		*/
		private $SrcPath;

		/**
		* Source file extension
		*/
		private $SrcExtension;

		/**
		* Source image width in px
		*/
		private $SrcWidth;

		/**
		* Source image height in px
		*/
		private $SrcHeight;

		/**
		* Source image MIME type
		*/
		private $SrcMimeType;

		/**
		* Thumb image width in px
		*/
		private $TmbWidth;

		/**
		* Thumb image heigh in px
		*/
		private $TmbHeight;

		/**
		* Thumb file path
		*/
		private $TmbPath;

		/**
		* Thumb image
		*/
		private $TmbImage;

		/**
		* Maximum memory allocation allowed
		*/
		private $MemoryLimit;

		/**
		* Initialize from a file
		* @param string File
		* @return void
		*/
		public function __construct($File) {
			// It's a file, at least?
			if (!is_file($File))
				throw new \Exception('This is not a file');

			// Get source info
			$Info = getimagesize($File);

			// Save the info
			$this->SrcPath = $File;
			$this->SrcWidth = $Info[0];
			$this->SrcHeight = $Info[1];
			$this->SrcMimeType = $Info['mime'];

			$ext = explode('.',$this->SrcPath);
			$this->SrcExtension = $ext[sizeof($ext)-1];
			unset($ext);

			// Get maximum memory limit
			$this->MemoryLimit = ini_get('memory_limit');

			// Convert the string into an integer
			switch (strtoupper($this->MemoryLimit[strlen($this->MemoryLimit)-1])) {
				default:
					$this->MemoryLimit = intval($this->MemoryLimit);
				break;
				case 'G':
					$this->MemoryLimit *= 1024;
				case 'M':
					$this->MemoryLimit *= 1024;
				case 'K':
					$this->MemoryLimit *= 1024;
			}

			// Subtract the memory we are currrently using
			$this->MemoryLimit;
		}

		/**
		* Load image
		* @return void
		*/
		private function Load() {
			// Is the image too big?
			if (($this->SrcHeight*$this->SrcWidth)*Thumbnail::PixelBytes+Thumbnail::TrueColorMemory > $this->MemoryLimit-memory_get_usage())
				throw new \Exception('Too large image');

			// Load it properly
			switch ($this->SrcMimeType) {
				default:
					// Unknown format? Say so.
					throw new \Exception('Unkown image format ('.$this->SrcMimeType.')');
				break;
				case 'image/jpeg':
					$this->SrcImage = imagecreatefromjpeg($this->SrcPath);
				break;
				case 'image/png':
					$this->SrcImage = imagecreatefrompng($this->SrcPath);
				break;
				case 'image/gif':
					$this->SrcImage = imagecreatefromgif($this->SrcPath);
				break;
			}
		}

		/**
		* Save thumbnail
		* @return void
		*/
		private function Save() {
			// We can impossibly have unknown type by now
			switch ($this->SrcMimeType) {
				case 'image/jpeg':
					$this->SrcImage = imagejpeg($this->TmbImage,$this->TmbPath);
				break;
				case 'image/png':
					$this->SrcImage = imagepng($this->TmbImage,$this->TmbPath);
				break;
				case 'image/gif':
					$this->SrcImage = imagegif($this->TmbImage,$this->TmbPath);
				break;
			}
		}

		/**
		* Create a thumbnail
		* @param int Width
		* @param int Height
		* @param int Dimensioning
		* @param int Resizing
		* @return string File path
		*/
		public function Get($Width,$Height,$Dimension=Thumbnail::DIMENSION_STATIC,$Resize=Thumbnail::RESIZE_FULLY) {
			// Save the size
			$this->TmbWidth = $Width;
			$this->TmbHeight = $Height;

			// Output dimensions?
			switch ($Dimension) {
				// Unknown code? Say so
				default:
					throw new \Exception('Unknown dimension code');
				break;

				// Do not resize?
				case Thumbnail::DIMENSION_STATIC:
				break;

				// Resize height according to proportions?
				case Thumbnail::DIMENSION_ANCHOR_WIDTH:
					$this->TmbHeight = round($this->SrcHeight*$this->TmbWidth/$this->SrcWidth);
				break;

				// Resize width according to proportions?
				case Thumbnail::DIMENSION_ANCHOR_HEIGHT:
					$this->TmbWidth = round($this->SrcWidth*$this->TmbHeight/$this->SrcHeight);
				break;

				// Maximum width?
				case Thumbnail::DIMENSION_MAXIMUM:
					// Width is longer on original? Anchor height.
					if ($this->SrcWidth/$this->SrcHeight > $this->TmbWidth/$this->TmbHeight)
						$this->TmbHeight = round($this->SrcHeight*$this->TmbWidth/$this->SrcWidth);
					// Height is longer on original? Anchor width.
					elseif ($this->SrcWidth/$this->SrcHeight < $this->TmbWidth/$this->TmbHeight)
						$this->TmbWidth = round($this->SrcWidth*$this->TmbHeight/$this->SrcHeight);
				break;

				// Minimum width?
				case Thumbnail::DIMENSION_MINIMUM:
					// Width is shorter on original? Calculate new height.
					if ($this->SrcWidth < $this->SrcHeight)
						$this->TmbHeight = round($this->SrcHeight*$this->TmbWidth/$this->SrcWidth);
					// Height is shorter on original? Calculate new width.
					elseif ($this->SrcHeight < $this->SrcWidth)
						$this->TmbWidth = round($this->SrcWidth*$this->TmbHeight/$this->SrcHeight);
				break;
			}

			// This is the thumb path
			$ext = explode('.',$this->SrcPath);
			unset($ext[sizeof($ext)-1]); // WARNING: This will break if no file extension exists
			$this->TmbPath = implode('.',$ext).'-'.$this->TmbWidth.'x'.$this->TmbHeight.'.'.strtolower($this->SrcExtension);
			unset($ext);

			// Return the thumb if it already exists
			if (file_exists($this->TmbPath))
				return $this->TmbPath;

			// Load the source image
			$this->Load();

			// Check if we have to required space for creating a thumbnail
			if (($this->TmbHeight*$this->TmbWidth)*Thumbnail::TrueColorBytes+Thumbnail::TrueColorMemory > $this->MemoryLimit-memory_get_usage())
				throw new \Exception('Too large thumbnail size');

			// Create the thumbnail
			$this->TmbImage = imagecreatetruecolor($this->TmbWidth,$this->TmbHeight);
			imagealphablending($this->TmbImage,false);
			imagesavealpha($this->TmbImage,true);

			// Copy source onto thumbnail
			imagecopyresampled($this->TmbImage,$this->SrcImage,0,0,0,0,$this->TmbWidth,$this->TmbHeight,$this->SrcWidth,$this->SrcHeight);

			// Save the file
			$this->Save();

			return $this->TmbPath;
		}
	}