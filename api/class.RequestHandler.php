<?php
	/**
	 * class.RequestHandler.php
	 * Copyright 2012 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge;

	/**
	 * A class to handle arbitrary HTTP requests through child classes
	 */
	abstract class RequestHandler {
		use Configurable;

		/**
		 * The requested path this handler should work with
		 * @var string
		 */
		private $path;

		/**
		 * The prefix to the requested path
		 * @var string
		 */
		private $prefix;

		/**
		 * Instantiate an object
		 * @param $prefix string The prefix that this request uses in the URL
		 * @param $path string Requested path to handle
		 */
		final public function __construct($prefix, $path) {
			$this->prefix = $prefix;
			$this->path = $path;
		}

		/**
		 * Create a new request handler for a given URL
		 * @param $path string Requested path to create a handler for
		 * @return RequestHandler
		 */
		final static public function factory($path) {
			$handlers = self::getConfig('handlers', array());
			$parts = explode('/', $path);

			for ($i=count($parts);$i>=0;--$i)
				if (isset($handlers[$base = implode('/', array_slice($parts, 0, $i))]))
					break;

			$request = implode('/', array_slice($parts, $i));

			if (!isset($handlers[$base]))
				throw new \forge\HttpException('No request handler could be located',
					\forge\HttpException::HTTP_NOT_FOUND);

			return new $handlers[$base]($base, $request);
		}

		/**
		 * Get this request's path
		 * @return string
		 */
		final protected function getPath() {
			return $this->path;
		}

		/**
		 * Get this request's prefix
		 * @return string
		 */
		final protected function getPrefix() {
			return $this->prefix;
		}

		/**
		 * Handle the request!
		 * @return void
		 */
		abstract public function handle();

		/**
		 * Set the Content-Length header
		 * @param $length Value to set the Content-Length field to
		 * @return void
		 */
		final static public function setContentLength($length) {
			header('Content-Length: '.(int)$length, true);
		}

		/**
		 * Set the Content-Type header
		 * @param $type string Value to set the Content-Type field to
		 * @return void
		 */
		final static public function setContentType($type) {
			header('Content-Type: '.$type, true);
		}

		/**
		 * Register a child RequestHandler class to handle a certain base URL
		 * @param $base string Base directory that the handler will listen on
		 * @param $handler RequestHandler The handler to register
		 * @return void
		 */
		final static public function register($base, $handler) {
			$handlers = self::getConfig('handlers');
			
			if (isset($handlers[$base]))
				throw new \Exception('The given base URL already has a handler registered to it.');

			if (!is_subclass_of($handler, '\forge\RequestHandler'))
				throw new \Exception('The given handler must extend \forge\RequestHandler');

			$handlers[$base] = $handler;
			
			self::setConfig('handlers', $handlers);
			self::writeConfig();
		}
	}