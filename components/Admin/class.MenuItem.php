<?php
	/**
	* class.MenuItem.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Admin;

	/**
	* Any addon who wish to append itself to the admin menu must implement this interface
	*/
	class MenuItem {
		private $active = false;
		private $children = [];
		private $icon;
		private $href;
		private $name;
		private $title;
		
		public function __construct($name, $title=null, $href=null, $icon='fa fa-link') {
			$this->href = $href;
			$this->icon = $icon;
			$this->name = $name;
			$this->title = $title;
		}
		
		public function appendChild(MenuItem $item) {
			$this->children[] = $item;
		}
		
		public function appendChildren($items) {
			foreach ($items as $item) {
				$master = null;
				foreach ($this->children as $subject)
					if ($item->getName() == $subject->getName()) {
						$master = $subject;
						break;
					}
				
				if ($master === null)
					$this->appendChild($item);
				else
					$master->merge($item);
			}
		}
		
		public function getChildren() {
			return $this->children;
		}
		
		public function getHREF() {
			return $this->href;
		}
		
		public function getIcon() {
			return $this->icon;
		}
		
		public function getLength() {
			return count($this->children);
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getTitle() {
			return $this->title;
		}
		
		public function hasChildren() {
			return count($this->children) > 0;
		}
		
		public function isActive() {
			return $this->active;
		}
		
		public function merge(MenuItem $item) {
			if ($item->getName() != $this->getName())
				throw new Exception('Not allowed to merge incompatible menu items.');
			
			if ($item->getTitle() !== null)
				$this->title = $item->getTitle();
			
			if ($item->getHREF() !== null)
				$this->href = $item->getHREF();
			
			$this->appendChildren($item->getChildren());
		}
		
		public function setActive(bool $active=true) {
			$this->active = $active;
		}
		
		public function setIcon(string $icon) {
			$this->icon = $icon;
		}
	}