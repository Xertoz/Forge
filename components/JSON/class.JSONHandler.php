<?php
	/**
	* class.JSONHandler.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\JSON;

	/**
	* The JSON view
	*/
	class JSONHandler extends \forge\RequestHandler {
		/**
		* Initialize the request
		*/
		public function handle() {
			// Try to find the feed and throw proper HTTP errors if it fails
			try {
				// Find out what addon and method to run
				preg_match('/^(\w+)\/(\w+)$/D', $this->getPath(), $matches);
				list($addon, $method) = array_splice($matches, 1);
				unset($matches);

				// Find the addon
				if (\forge\Addon::existsComponent($addon))
					$class = 'forge\\components\\'.$addon;
				elseif (\forge\Addon::existsModule($addon))
					$class = 'forge\\modules\\'.$addon;
				else
					throw new \forge\HttpException(_('The requested feed does not exist'), \forge\HttpException::HTTP_NOT_FOUND);

				// Find its AJAX definition
				if (!class_exists($json = $class.'\JSON'))
					throw new \forge\HttpException(_('JSON definition not found'), \forge\HttpException::HTTP_NOT_IMPLEMENTED);

				// Does the method exist?
				if (!method_exists($json, $method))
					throw new \forge\HttpException(_('AJAX method not found'), \forge\HttpException::HTTP_NOT_IMPLEMENTED);
			}
			catch (\forge\HttpException $e) {
				throw $e;
			}
			catch (\Exception $e) {
				throw new \forge\HttpException('URL not found',\forge\HttpException::HTTP_NOT_FOUND);
			}

			// We know where it is - time to try and execute it
			try {
				$return = call_user_func($json.'::'.$method);
				
				$this->setContentType('application/json;charset=UTF-8');
				echo json_encode($return);
			}
			catch (\forge\HttpException $e) {
				header($e->getHttpHeader(), true, $e->getCode());
				
				echo json_encode(['error' => $e->getMessage()]);
			}
		}
	}