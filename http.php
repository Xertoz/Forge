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

	// Load the requested or configured locale
	if (!\forge\components\Locale::loadLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE'])))
		\forge\components\Locale::loadLocale();
	
	// Respond to the HTTP request
	try {
		// Don't stop processing becaues of the client
		ignore_user_abort(true);
		
		// Buffer the output data
		ob_start();
		
		// Handle any POST data
		Controller::handle();

		if (strstr($_SERVER['HTTP_ACCEPT'], 'application/json') === false) {
			// Factor a request handler and let it run
			$handler = RequestHandler::factory($url);
			$handler->handle();
		}
		else {
			if (Controller::getCode() == Controller::RESULT_BAD)
				header('HTTP/1.1 400 Bad Request');

			echo json_encode([
				'code' => Controller::getCode(),
				'controller' => Controller::getController(),
				'exception' => Controller::getException(),
				'message' => Controller::getMessage()
			]);
		}
		
		// Close the connection to the HTTP client
		header('Content-Encoding: none');
		header('Content-Length: '.ob_get_length());
		header('Connection: close');
		ob_end_flush();
		flush();
		session_write_close();
		
		// Note some statistics
		\forge\components\Statistics::runCount();
		
		// Handle any cronjobs
		\forge\components\Cron::runJobs();
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