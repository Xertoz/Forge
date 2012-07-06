<?php
    /**
    * class.AdminHandler.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Admin;

    /**
    * The view that accounts for administration requests.
    */
    class AdminHandler extends \forge\RequestHandler {
        /**
        * Addon to utilize in the request
        * @var string
        */
        protected $addon;

        /**
        * Method to invoke from the addon
        * @var string
        */
        protected $view;

        /**
        * Initialize the request
        */
        public function handle() {
            $parts = explode('/',$this->getPath());

            $this->addon = !empty($parts[0]) ? $parts[0] : 'Dashboard';
            $this->view = !empty($parts[1]) ? $parts[1] : 'index';

            \forge\components\Templates::addScript('<script type="text/javascript" src="/script/ckeditor/ckeditor.js"></script>');
            \forge\components\Templates::addScript('<script type="text/javascript" src="/script/forge/forge.js"></script>');
            \forge\components\Templates::addScript('<script type="text/javascript" src="/script/forge/forge.admin.js"></script>');

            \forge\components\Templates::addStyle('<link href="/css/admin.css" rel="stylesheet" media="screen" />');

            $this->setContentType('text/html;charset=UTF-8');
            echo \forge\components\Admin::display($this->addon,$this->view);
        }
    }