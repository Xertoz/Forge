<?php
	/**
	* http.php
	* Copyright 2009-2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	use forge\components\Cron;
	use forge\components\Databases\TableList;
	use forge\components\Identity;
	use forge\components\Locale;
	use forge\components\SiteMap;
	use forge\components\SiteMap\db\History;
	use forge\components\SiteMap\db\Page;
	use forge\components\Statistics;
	use forge\components\Templates;
	use forge\components\Templates\Engine;

	// Load the Forge system
	require_once 'forge.php';

	// Tell the client about us
	header('Accept-Charset: utf-8', true);
	header('X-Powered-By: PHP/'.phpversion().' Forge/'.FORGE_VERSION, true);
	
	// Get the relative URL that was requested
	$url = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);

	// Make sure the developers see all debug info available
	if (Identity::isDeveloper()) {
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 'on');
	}

	// Load the requested or configured locale
	if (!Locale::loadLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE'])))
		Locale::loadLocale();
	
	// Respond to the HTTP request
	try {
		// Don't stop processing becaues of the client
		ignore_user_abort(true);
		
		// Buffer the output data
		ob_start();
		
		// Handle any POST data
		Controller::handle();

		if (strstr($_SERVER['HTTP_ACCEPT'], 'application/json') === false) {
			// Did the user hit up the root index?
			if (!$url) try {
				$page = new Page();
				$page->page_default = true;
				$page->select('page_default');
				/**
				 * @var \forge\components\SiteMap\Page $type
				 */
				$type = new $page->page_type($page);
			}
			catch (\Exception $e) {
				throw new HttpException('Page not found', HttpException::HTTP_NOT_FOUND);
			}
			else {
				// Compile all possible URLs
				$folders = explode('/', $url);
				$urls = [];
				for ($i=count($folders);$i>0;--$i)
					$urls[] = implode('/', array_slice($folders, 0, $i));

				// Look for the longest matching URL
				$pages = new TableList([
					'type' => new \forge\components\SiteMap\db\Page(),
					'where' => [
						'in:page_url' => $urls
					],
					'order' => ['page_url' => 'DESC'],
					'limit' => 1
				]);

				// If we have a match, make sure it is exact or dynamic
				if ($pages->length() === 1) {
					$page = $pages[0];

					/**
					 * @var \forge\components\SiteMap\Page $type
					 */
					$type = new $page->page_type($page);

					if ($page->page_url !== $url && !$type->isDynamic())
						throw new HttpException('Page not found', HttpException::HTTP_NOT_FOUND);
				}

				// If we found nothing here, first look into the history before telling 404
				if (!isset($page)) {
					$history = new History();
					$history->url = $url;

					// Just tell the client 404 if we lack history
					try {
						$history->select('url');
					}
					catch (\Exception $e) {
						throw new HttpException('Page not found',HttpException::HTTP_NOT_FOUND);
					}

					// Are we going to redirect?
					if ($history->http === HttpException::HTTP_MOVED_PERMANENTLY)
						SiteMap::redirect($history->redirect, $history->http);

					// Otherwise, just throw a new exception
					throw new HttpException('Page not found', $history->http);
				}
			}

			Engine::setTitle($page->page_title);
			Templates::setMeta(array(
				'description' => $page->meta_description,
				'keywords' => $page->meta_keywords,
				'language' => Locale::getLocale()
			));
			Templates::setVar('page', $page);
			Templates::setVar('type', $type);

			$uri = ($s = strpos($_SERVER['REQUEST_URI'],'?')) !== false ? substr($_SERVER['REQUEST_URI'],1,--$s) : substr($_SERVER['REQUEST_URI'],1);

			echo $type->view($page,array(
				'REQ_URI' => $uri,
				'PAGE_URI' => $page->page_url,
				'SUB_URI' => substr($uri,1+strlen($page->page_url))
			));
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
		Statistics::runCount();
		
		// Handle any cronjobs
		Cron::runJobs();
	}
	catch (HttpException $e) {
		// Set the appropriate response header
		header($e->getHttpHeader(),$e->getCode());
		require file_exists($file = 'errors/'.$e->getCode().'.html') ? $file : 'errors/500.html';
	}
	catch (\Exception $e) {
		header('HTTP/1.1 500 Internal Server Error');
		
		if (Identity::isDeveloper())
			require 'errors/dump.php';
		else
			require 'errors/500.html';
	}