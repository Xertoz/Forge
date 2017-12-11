<?php
	/**
	* class.Set.php
	* Copyright 2012-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates\controllers;

	/**
	* Handle page models through HTTP
	*/
	class Set extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 */
		public function process() {
			\forge\components\Identity::restrict('com.Templates.Admin');
			
			try {
	
				if (empty($_POST['template']))
					throw new Exception('No template was chosen');
	
				\forge\components\Templates::setTemplate($_POST['template'], true);
				
				self::setResponse(self::l('The default template was changed!'), self::RESULT_OK);
			}
			catch (\Exception $e) {
				self::setResponse($e->getMessage(), self::RESULT_BAD);
			}
		}
	}