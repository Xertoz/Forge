<?php
    /**
    * com.Admin.php
    * Copyright 2011-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components;

    /**
    * Administration component
    */
    final class Admin extends \forge\Component {
        /**
        * Permissions
        * @var array
        */
        static protected $permissions = array(
            'Admin' => array(
                'administration' => array(
                    'access'
                )
            )
        );

        /**
        * Display the administration panel
        * @param string Name of the addon
        * @param string Name of the view
        * @return string
        * @throws Exception
        */
        static public function display($addon,$view) {
            // First off, we must be admin
            try {
                \forge\components\Accounts::restrict('Admin','administration','access','r');
            }
            catch (\Exception $e) {
                \forge\components\SiteMap::redirect('/user/login?from='.urlencode($_SERVER['REQUEST_URI']));
            }

            // Argument typing
            $addon = (string)$addon;
            $view = (string)$view;

            // First off, find the component or module. If it cannot be found, show the 404 page and what not.
            if (\forge\Addon::existsComponent($addon))
                $addon = '\forge\components\\'.$addon;
            elseif (\forge\Addon::existsModule($addon))
                $addon = '\forge\modules\\'.$addon;
            else
                throw new \forge\HttpException('Addon '.$addon.' was not found',\forge\HttpException::HTTP_NOT_FOUND);

            // Does it implement an administration interface?
            if (!class_exists($class = $addon.'\\Admin'))
                throw new \forge\HttpException('The addon does not implement an administration interface',\forge\HttpException::HTTP_NOT_FOUND);
            if (!in_array('forge\components\Admin\Administration', class_implements($class)))
                throw new \forge\HttpException(_('The addon did not invoke a proper admin interface'),\forge\HttpException::HTTP_NOT_IMPLEMENTED);

            // If the view exists, then we should return the administration output
            if (method_exists($class,$view))
                return \forge\components\Templates::display(
                    'components/Admin/tpl/page.view.php',
                    array(
                        'admin' => true,
                        'view' => $class::$view()
                    )
                );

            // If we haven't reached the return line above, we've 404'd  the view
            throw new \forge\HttpException('View was not found',\forge\HttpException::HTTP_NOT_FOUND);
        }
    }