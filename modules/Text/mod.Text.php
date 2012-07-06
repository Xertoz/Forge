<?php
    /**
    * mod.Text.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\modules;
    use \forge\Module;

    require_once 'modules/Text/db/tbl.textpages.php';

    require_once 'modules/Text/ajax.Text.php';
    require_once 'modules/Text/api/page.Text.php';

    /**
    * A text page module for Forge 4
    */
    class Text extends Module {
        /**
        * Component name
        * @var string
        */
        protected $name = 'Text';

        /**
        * Component version
        * @var string
        */
        static protected $version = FORGE_VERSION;
    }