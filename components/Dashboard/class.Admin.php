<?php
    /**
    * acp.Dashboard.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components\Dashboard;

    /**
    * Dashboard component of Forge 4
    * Administration interface
    */
    class Admin implements \forge\components\admin\Administration {
        static public function index() {
            $infoboxes = array();

            foreach (\forge\Addon::getComponents(true) as $com)
                if (in_array('forge\components\Dashboard\InfoBox',class_implements($com)))
                    $infoboxes[] = call_user_func($com.'::getInfobox');

            foreach (\forge\Addon::getModules(true) as $mod)
                if (in_array('forge\components\Dashboard\InfoBox',class_implements($mod)))
                    $infoboxes[] = call_user_func($mod.'::getInfobox');

            return \forge\components\Templates::display(
                array(
                    'components/Dashboard/tpl/adm.gui.php'
                ),
                array(
                    'infoboxes'=>$infoboxes
                )
            );
        }
    }