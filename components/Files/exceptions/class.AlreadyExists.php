<?php
	/**
	* class.FileNotFound.php
	* Copyright 2015 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Files\exceptions;
	
	class AlreadyExists extends \Exception {
		public function __construct() {
			parent::__construct('A file or folder already exists by this name');
		}
	}