<?php
	/**
	 * install.php
	 * Copyright 2012 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge\installation;

	// Start off by initializing Forge
	chdir('..');
	require_once 'forge.php';

	/**
	 * Get a parameter from the developer
	 * @param $description string Parameter description
	 * @param $required boolean Should the execution stop if the parameter is lacking?
	 * @return string
	 * @internal
	 */
	function param($description, $required=true) {
		do {
			echo $description.': ';
			$param = trim(fgets(STDIN));
		}
		while ($required && !strlen($param));

		return $param;
	}

	// Make sure we're not reinstalling
	if (count(glob('config/*')))
		die("Forge has already been installed\n");
	
	// Make sure we can write to files
	if (!is_writable('config') || !is_writeable('files'))
		die("Required writing permissions were not given\n");

	// Get the initial hostname to listen on
	$hostname = param('Hostname');

	// Get all required input fields from the developer
	$root = array(
		'username' => param('Root username'),
		'password' => param('Root password')
	);

	// Get a developer key
	$developer = param('Developer key');

	// Get database driver
	$database = array();
	do {
		$database['driver'] = param('Database driver');
	}
	while ($database['driver'] != 'mysql');

	// Get MySQL parameters
	if ($database['driver'] == 'mysql') {
		$database['driver'] = 'MySQL';
		$database['hostname'] = param('MySQL hostname');
		$database['database'] = param('MySQL database');
		$database['prefix'] = param('MySQL table prefix', false);
		$database['username'] = param('MySQL username');
		$database['password'] = param('MySQL password', false);
	}

	// Now that everything is known, attempt the installation
	try {
		// Start with all request handlers that Forge utilizes itself
		$handlers = array(
				'user' => 'forge\components\Accounts\UserHandler',
				'admin' => 'forge\components\Admin\AdminHandler',
				//'files' => 'forge\components\Files\FileRequest',
				'thumbnail' => 'forge\components\Files\ThumbnailRequest',
				null => 'forge\components\SiteMap\PageHandler',
				'robots.txt' => 'forge\components\SiteMap\SiteMapHandler',
				'sitemap' => 'forge\components\SiteMap\SiteMapHandler',
				'xml' => 'forge\components\XML\XMLHandler',
				'json' => 'forge\components\JSON\JSONHandler'
		);
		foreach ($handlers as $base => $handler)
			\forge\RequestHandler::register($base, $handler);
		
		// Set up the root account
		\forge\components\Accounts::setRoot($root['username'], $root['password']);

		// Write the developer key
		\forge\components\Accounts::setDeveloperKey($developer);

		// Set up the database connection
		$cId = \forge\components\Databases::AddConnection(
			$database['driver'],
			$database['hostname'],
			$database['database'],
			$database['prefix'],
			$database['username'],
			$database['password']
		);
		\forge\components\Databases::SetDefaultConnection($cId);
		foreach (\forge\Addon::getComponents() as $component)
			\forge\components\Databases::fixDatabase($component,'COM');

		// Set up the host
		$website = new \forge\components\Websites\db\Website();
		$website->domain = $hostname;
		$website->insert();
		
		// Set the default template
		\forge\components\Templates::setTemplate('anvil', true, $hostname);
	}
	catch (\Exception $e) {
		// Remove any configured files
		if (($files = glob('config/*')) !== false)
			foreach ($files as $file)
				(new \forge\components\Files\ConfigFile(substr($file, strlen('config/'))))->delete();

		// Tell the developer about this!
		echo "Something went wrong during the installation. Exception thrown:\n";
		echo $e->getMessage()."\n";
		echo $e->getTraceAsString().PHP_EOL;

		die(1);
	}

	// Return without error
	echo "Forge was successfully installed on http(s)://$hostname/\n";