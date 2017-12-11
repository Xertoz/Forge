<?php
	/**
	* class.Controller.php
	* Copyright 2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* Controller class for handling HTTP POST data
	*/
	abstract class Controller {
		use \forge\components\Locale\Translator;
		
		/**
		 * Something went wrong while running the controller
		 */
		const RESULT_BAD = -1;
		
		/**
		 * The controller was run properly
		 */
		const RESULT_OK = 1;
		
		/**
		 * The controller was not run yet
		 */
		const RESULT_PENDING = 0;
		
		/**
		 * Response code of the controller request
		 */
		static protected $code = 0;
		
		/**
		 * Name of the controller ran
		 */
		static protected $controller = null;
		
		/**
		 * The exception, if one was thrown
		 * @var type \forge\HttpException
		 */
		static protected $exception = null;
		
		/**
		 * Response message of the controller request
		 */
		static protected $message = null;
		
		/**
		 * Search the POST data for a component request and run it
		 * @return void
		 */
		static final public function handle() {
			if (!isset($_POST['forge']['controller']))
				return;
			
			self::$controller = $_POST['forge']['controller'];

			try {
				list($addon, $controller) = explode('\\', self::$controller);
			}
			catch (\Exception $e) {
				return;
			}
			
			$class = 'forge\\'.(Addon::existsComponent($addon) ? 'components' : 'modules').'\\'.$addon.'\\controllers\\'.$controller;

			if (class_exists($class) && in_array('forge\\Controller', class_parents($class))) {
				try {
					(new $class)->process();
				}
				catch (\forge\HttpException $e) {
					self::setResponse($e->getMessage(), self::RESULT_BAD);
					self::$exception = $e;
				}

				if (self::getCode() == self::RESULT_OK && isset($_POST['forge']['redirect']))
					\forge\components\SiteMap::redirect($_POST['forge']['redirect'], 302);
			}
		}
		
		/**
		 * Get the response code
		 * @return int
		 */
		static final public function getCode() {
			return (int)self::$code;
		}
		
		/**
		 * Get the name of the ran controller
		 * @return string
		 */
		static final public function getController() {
			return self::$controller;
		}
		
		/**
		 * Get the exception, if one was thrown
		 * @return \forge\HttpException
		 */
		static final public function getException() {
			return self::$exception;
		}
		
		/**
		 * Get the response message
		 * @return int
		 */
		static final public function getMessage() {
			return (string)self::$message;
		}
		
		/**
		 * Process POST data
		 * @return void
		 */
		abstract public function process();
		
		/**
		 * Set a response code
		 * @param $code int Response code (should be set to a class constant)
		 * @return void
		 */
		static final protected function setCode($code) {
			self::$code = (int)$code;
		}
		
		/**
		 * Set a response message
		 * @param $message int Response message
		 * @return void
		 */
		static final protected function setMessage($message) {
			self::$message = (string)$message;
		}
		
		/**
		 * Set a response message
		 * @param $message int Response message
		 * @param $code int Response code (should be set to a class constant)
		 * @return void
		 */
		static final protected function setResponse($message, $code) {
			self::$code = $code;
			self::$message = $message;
		}
	}