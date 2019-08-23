<?php require_once '../forge.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Forge Unit Tester</title>
		<link href="/css/tools.css" rel="stylesheet" media="screen" />
	</head>
	<body>
		<h1>Unit Test</h1>
		<p>This tool will run a series of tests on the installed Forge system and produce results for inspection.</p>
		<?php
		/**
		 * Check an URL for any errors
		 * @param $url string URL
		 * @return boolean
		 */
		function http($url) {
			if (!extension_loaded('curl') || ini_get('open_basedir'))
				return false;
			$curl = curl_init(HTTP_PREFIX.$url);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($curl);

			return curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200;
		}
		define('HTTP_PREFIX', 'http://'.$_SERVER['HTTP_HOST'].'/');

		function check_extension(\forge\Checklist $chk, $ext) {
			$chk->add(extension_loaded($ext), sprintf('PHP extension %s is loaded.', $ext));
		}

		function check_url(\forge\Checklist $chk, $url) {
			$chk->add(http($url), sprintf('The address /%s is reachable.', $url));
		}

		$unit = new \forge\Checklist();
		$unit->add(true, 'Forge is loadable.');
		$unit->add(count(glob(FORGE_PATH.'/config/*')) > 0, 'Forge is installed.');
		$unit->add(is_writable(FORGE_PATH.'/config'), 'The config folder is writable.');
		$unit->add(is_writable(FORGE_PATH.'/files'), 'The files folder is writable.');
		$unit->add(substr(phpversion(), 0, strlen('5.4')) >= 5.4, 'PHP version is at least 5.4.');
		check_extension($unit, 'curl');
		check_extension($unit, 'fileinfo');
		check_extension($unit, 'gd');
		check_extension($unit, 'hash');
		check_extension($unit, 'intl');
		check_extension($unit, 'pcre');
		check_extension($unit, 'PDO');
		check_extension($unit, 'PDO_mysql');
		check_extension($unit, 'session');
		check_extension($unit, 'xmlwriter');
		check_url($unit, 'user');
		check_url($unit, 'user/login');
		check_url($unit, 'user/logout');
		check_url($unit, 'user/lost-password');
		check_url($unit, 'user/register');
		check_url($unit, 'user/register/success');
		check_url($unit, 'user/settings');
		check_url($unit, 'admin');
		check_url($unit, 'robots.txt');
		check_url($unit, 'sitemap');
		check_url($unit, 'sitemap/xml');
		check_url($unit, 'sitemap/xsl');
		check_url($unit, '');
		echo $unit;
		?>
	</body>
</html>
