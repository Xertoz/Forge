<?php
	namespace forge\components\Files;
	
	class JSON {
		static public function upload() {
			\forge\components\Identity::restrict('com.Files.Admin');
			
			$id = \forge\Post::getInt('id');
			if ($id === null)
				throw new \forge\HttpException('Required parameter "id" not set',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			$path = \forge\Post::getString('path');
			if ($path === null)
				throw new \forge\HttpException('Required parameter "path" not set',
						\forge\HttpException::HTTP_BAD_REQUEST);
			
			try {
				$repo = new Repository($id);
				$folder = $repo->getFolder($path);
				$folder->uploadFiles($_FILES['files']);
			}
			catch (\Exception $e) {echo $e->getMessage();
				throw new \forge\HttpException('The file could not be uploaded!',
						\forge\HttpException::HTTP_CONFLICT);
			}
			
			return true;
		}
	}