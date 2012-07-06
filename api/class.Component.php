<?php
    /**
    * class.Component.php
    * Copyright 2009-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge;

    /**
    * Supply the component class template
    */
    abstract class Component extends Addon {
        /**
        * The component's version number should equal Forge's version
        * @var string
        */
        static protected $version = \FORGE_VERSION;
    }