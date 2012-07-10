<?php
	/**
	* class.Event.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge;

	/**
	* Event handler
	*/
	class Event {
		/**
		* Name identifier of the event
		* @var string
		*/
		protected $name;

		/**
		* @param string Event identifier
		* @param bool Fire the event immediately?
		*/
		public function __construct($name='Generic',$fire=false) {
			$this->name = (string)$name;

			if ($fire)
				$this->fire();
		}

		/**
		* Fire off the event to all listeners
		* @return int Number of listening handlers
		*/
		final public function fire() {
			$addons = \forge\Addon::getAddons(true);
			$fired = 0;

			foreach ($addons as $addon)
				if (in_array('forge\EventListener',class_implements($addon)))
					try {
						$addon::event($this);
						$fired++;
					}
					catch (\Exception $e) {}

			return $fired;
		}

		/**
		* Get the name of this event
		*/
		public function getName() {
			return $this->name;
		}
	}