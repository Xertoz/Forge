<?php
	/**
	 * class.Checklist.php
	 * Copyright 2013 Mattias Lindholm
	 *
	 * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	 * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	 */

	namespace forge;

	/**
	 * A checklist is a list of conditions with descriptive texts which were or were not met
	 */
	class Checklist {
		private $list = [];

		/**
		 * Produce a list of checked items
		 * @return string
		 */
		public function __toString() {
			$ul = '<ul class="checklist">';

			foreach ($this->list as $item) {
				$description = \forge\components\Templates\Engine::html($item['description']);
				$ul .= '<li class="'.($item['check'] ? 'success' : 'failure').'">'.$description.'</li>';
			}

			$ul .= '</ul>';

			return $ul;
		}

		/**
		 * Add a check to the list
		 * @param bool $check
		 * @param string $description
		 */
		public function add($check, $description) {
			$this->list[] = ['check' => $check, 'description' => $description];
		}

		/**
		 * Did all checks turn out OK?
		 * @return bool
		 */
		public function isChecked() {
			foreach ($this->list as $item)
				if (!$item['check'])
					return false;

			return true;
		}
	}