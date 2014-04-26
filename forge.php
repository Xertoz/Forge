<?php
	/**
	* forge.php
	* Copyright 2009-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;
	
	// Set the version number of this software
	define('FORGE_VERSION', '5.0.0-dev');
	
	// Set the root path for the Forge system
	define('FORGE_PATH', __DIR__);
	
	// As a time zone must be set, use UTC for convenience
	date_default_timezone_set('UTC');

	/**
	* Throw errors generated
	* This function will ALWAYS throw an Exception
	* @param int $errno
	* @param string $msg
	* @param string $file
	* @param int $line
	* @return void
	* @throws Exception
	*/
	function error_handler($errno, $msg, $file, $line) {
		throw new \Exception($file.'('.$line.'): '.$msg, $errno);
	}
	
	// Bind the error handler
	set_error_handler('forge\error_handler', E_ALL);
	
	/**
	 * An autoloader for the Forge environment
	 * @param string $subject The name of the class that was not loaded yet
	 * @internal
	 */
	function autoload($subject) {
		// Split the subject up for inspection
		$namespace = explode('\\', $subject);
		
		// The root namespace must be forge, and we can discard it
		if (array_shift($namespace) != 'forge')
			return;
		
		// Fetch the class name out of the array
		$class = array_pop($namespace);
		
		// Depending on the first subspace, choose a path
		switch (array_shift($namespace)) {
			default:
				$path = 'api';
				$type = 'class';
				break;
			
			case 'components':
				$path = 'components/'.(($count = count($namespace)) ? implode('/', $namespace) : $class);
				$type = $count ? 'class' : 'com';
				break;
			
			case 'modules':
				$path = 'modules/'.(($count = count($namespace)) ? implode('/', $namespace) : $class);
				$type = $count ? 'class' : 'mod';
				break;
		}
		
		// Build a file path & name
		$file = FORGE_PATH.'/'.$path.'/'.$type.'.'.$class.'.php';
		
		// If it exists, include it
		if (file_exists($file))
			require_once $file;
	}
	
	// Set up the autoloader for Forge
	spl_autoload_register('forge\autoload', true, false);
