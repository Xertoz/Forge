<?php
	/**
	* http.php
	* Copyright 2009-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	// Load the Forge system
	require_once 'forge.php';

	// Tell the client about us
	header('Accept-Charset: utf-8', true);
	header('X-Powered-By: PHP/'.phpversion().' Forge/'.FORGE_VERSION, true);
	
	// Get the relative URL that was requested
	$url = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);

	// Make sure the developers see all debug info available
	if (\forge\components\Identity::isDeveloper()) {
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 'on');
	}
	
	// Respond to the HTTP request
	try {
		// Handle any POST data
		Controller::handle();
		
		// Make sure we are on a correct host
		components\Websites::loadWebsite();
		
		// Factor a request handler and let it run
		$handler = RequestHandler::factory($url);
		$handler->handle();
	}
	catch (\forge\HttpException $e) {
		// Tell the user about the error in HTML
		\forge\RequestHandler::setContentType('text/html;charset=UTF-8');
		
		// Set the appropriate response header
		header($e->getHttpHeader(),$e->getCode());
		require file_exists($file = 'errors/'.$e->getCode().'.html') ? $file : 'errors/500.html';
	}
	catch (\Exception $e) {
		\forge\RequestHandler::setContentType('text/html;charset=UTF-8');
		header('HTTP/1.1 500 Internal Server Error');
		
		// Tell any caring code about what happened
		$event = new Event('UncaughtException');
		$event->exception = $e;
		$event->fire();
		
		if (\forge\components\Identity::isDeveloper())
			require 'errors/dump.php';
		else
			require 'errors/500.html';
	}