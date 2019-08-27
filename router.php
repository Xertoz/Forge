<?php
    /**
     * router.php
     * Copyright 2016 Mattias Lindholm
     *
     * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
     * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
     * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
     */

    $dir = explode('/', $_SERVER['REQUEST_URI'])[1];
    if (in_array($dir, ['css', 'images', 'script', 'tools', 'templates', 'components', 'modules', 'swap'])) {
        $types = [
            'css' => 'text/css',
            'flv' => 'video/x-flv',
            'htm' => 'text/html',
            'html' => 'text/html',
            'gif' => 'image/gif',
            'ico' => 'image/vnd.microsoft.icon',
            'jpg' => 'image/jpeg',
            'js' => 'application/javascript',
            'php' => 'text/html',
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'swf' => 'application/x-shockwave-flash',
            'txt' => 'text/plain',
            'xml' => 'application/xml'
        ];
        $ext = explode('.', $_SERVER['REQUEST_URI']);
        $ext = array_pop($ext);

        if ($ext == 'php') {
            $dir = explode('/', $_SERVER['REQUEST_URI']);
            $file = array_pop($dir);
            $dir = '.'.implode('/', $dir);
            chdir($dir);

            require_once $file;
        }
        else {
            $type = isset($types[$ext]) ? $types[$ext] : 'text/plain';

            $path = '.' . $_SERVER['REQUEST_URI'];
            $fh = fopen($path, 'rb');
            header('Content-Type: ' . $type);
            header('Content-Length: ' . filesize($path));
            fpassthru($fh);
            fclose($fh);
        }
    }
    else
        require 'http.php';