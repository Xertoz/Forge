<?php
    /**
     * unit.php
     * Copyright 2012 Mattias Lindholm
     *
     * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
     * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
     * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
     */

    // We work from the project's root folder
    chdir('..');

    /**
     * Check an expression for failure or passing status
     * @param $what string Descriptive text about the expression
     * @param $was boolean Expression
     * @return void
     */
    function evaluate($what, $was) {
        echo $what.'... ';

        if ($was)
            echo "OK\n";
        else
            die("ERROR\n");
    }

    /**
     * Check an URL for any errors
     * @param $url string URL
     * @return boolean
     */
    function http($url) {
        $curl = curl_init(HTTP_PREFIX.$url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curl);

        return curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200;
    }

    // Get the host off the command line
    evaluate('Checking for hostname', isset($argv[1]));
    define('HTTP_PREFIX', 'http://'.$argv[1].'/');

    // Require PHP 5.4.x
    evaluate('Checking for PHP 5.4.x', substr(phpversion(), 0, strlen('5.4')) == '5.4');

    // Check for extension dependencies
    evaluate('Checking for curl', extension_loaded('curl'));
    evaluate('Checking for fileinfo', extension_loaded('fileinfo'));
    evaluate('Checking for gettext', extension_loaded('gettext'));
    evaluate('Checking for gd', extension_loaded('gd'));
    evaluate('Checking for hash', extension_loaded('hash'));
    evaluate('Checking for pcre', extension_loaded('pcre'));
    evaluate('Checking for PDO', extension_loaded('PDO'));
    evaluate('Checking for pdo_mysql', extension_loaded('pdo_mysql'));
    evaluate('Checking for session', extension_loaded('session'));
    evaluate('Checking for xmlwriter', extension_loaded('xmlwriter'));

    // Check for writable files
    evaluate('Checking for writable configuration', is_writeable('config'));
    evaluate('Checking for writable swap', is_writeable('swap'));

    // Check for installed copy of Forge
    evaluate('Checking for installation', count(glob('config/*.php')));

    // Check for various pages to be working
    evaluate('Checking for /engine/info', http('engine/info'));
    evaluate('Checking for /user', http('user'));
    evaluate('Checking for /user/login', http('user/login'));
    evaluate('Checking for /user/logout', http('user/logout'));
    evaluate('Checking for /user/lost-password', http('user/lost-password'));
    evaluate('Checking for /user/recover-password', http('user/recover-password'));
    evaluate('Checking for /user/register', http('user/register'));
    evaluate('Checking for /user/register/success', http('user/register/success'));
    evaluate('Checking for /user/settings', http('user/settings'));
    evaluate('Checking for /admin', http('admin'));
    evaluate('Checking for /robots.txt', http('robots.txt'));
    evaluate('Checking for /sitemap', http('sitemap'));
    evaluate('Checking for /sitemap/xml', http('sitemap/xml'));
    evaluate('Checking for /sitemap/xsl', http('sitemap/xsl'));
    evaluate('Checking for /', http(''));