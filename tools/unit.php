<?php require_once '../forge.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo _('Forge Unit Tester'); ?></title>
		<link href="/css/tools.css" rel="stylesheet" media="screen" />
	</head>
	<body>
		<h1><?php echo _('Unit Test'); ?></h1>
		<p><?php echo _('This tool will run a series of tests on the installed Forge system and produce results for inspection.'); ?></p>
		<?php
		/**
		 * Check an URL for any errors
		 * @param $url string URL
		 * @return boolean
		 */
		function http($url) {
			if (!extension_loaded('curl'))
				return false;
			$curl = curl_init(HTTP_PREFIX.$url);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($curl);

			return curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200;
		}
		define('HTTP_PREFIX', 'http://'.$_SERVER['HTTP_HOST'].'/');

		$unit = new \forge\Checklist();
		$unit->add(true, _('Forge is loadable.'));
		$unit->add(count(glob(FORGE_PATH.'/config/*')) > 0, _('Forge is installed.'));
		$unit->add(is_writable(FORGE_PATH.'/config'), _('The config folder is writable.'));
		$unit->add(is_writable(FORGE_PATH.'/files'), _('The files folder is writable.'));
		$unit->add(substr(phpversion(), 0, strlen('5.4')) == '5.4', _('PHP version is in the 5.4 family.'));
		$unit->add(extension_loaded('curl'), _('PHP extension curl is loaded.'));
		$unit->add(extension_loaded('fileinfo'), _('PHP extension fileinfo is loaded.'));
		$unit->add(extension_loaded('gettext'), _('PHP extension gettext is loaded.'));
		$unit->add(extension_loaded('gd'), _('PHP extension gd is loaded.'));
		$unit->add(extension_loaded('hash'), _('PHP extension hash is loaded.'));
		$unit->add(extension_loaded('pcre'), _('PHP extension pcre is loaded.'));
		$unit->add(extension_loaded('PDO'), _('PHP extension PDO is loaded.'));
		$unit->add(extension_loaded('pdo_mysql'), _('PHP extension pdo_mysql is loaded.'));
		$unit->add(extension_loaded('session'), _('PHP extension session is loaded.'));
		$unit->add(extension_loaded('xmlwriter'), _('PHP extension xmlwriter is loaded.'));
		$unit->add(http('user'), _('The address /user is reachable.'));
		$unit->add(http('user/login'), _('The address /user/login is reachable.'));
		$unit->add(http('user/logout'), _('The address /user/logout is reachable.'));
		$unit->add(http('user/lost-password'), _('The address /user/lost-password is reachable.'));
		$unit->add(http('user/lost-password'), _('The address /user/lost-password is reachable.'));
		$unit->add(http('user/register'), _('The address /user/register is reachable.'));
		$unit->add(http('user/register/success'), _('The address /user/register/success is reachable.'));
		$unit->add(http('user/settings'), _('The address /user/settings is reachable.'));
		$unit->add(http('admin'), _('The address /admin is reachable.'));
		$unit->add(http('robots.txt'), _('The address /robots.txt is reachable.'));
		$unit->add(http('sitemap'), _('The address /sitemap is reachable.'));
		$unit->add(http('sitemap/xml'), _('The address /sitemap/xml is reachable.'));
		$unit->add(http('sitemap/xsl'), _('The address /sitemap/xsl is reachable.'));
		$unit->add(http(''), _('The address / is reachable.'));
		echo $unit;
		?>
	</body>
</html>