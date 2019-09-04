<?php
	/**
	* class.Robots.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap\controllers;

	/**
	* Handle robot settings
	*/
	class Robots extends \forge\Controller {
		/**
		 * Process POST data
		 * @return void
		 * @throws \forge\HttpException
		 */
		public function process() {
			\forge\components\Identity::restrict('com.SiteMap.Robots');

			if (!isset($_POST['enable']))
				throw new \forge\HttpException('You must specify a new setting', \forge\HttpException::HTTP_BAD_REQUEST);

			\forge\components\SiteMap::setRobots($_POST['enable']);

			self::setResponse(self::l('Your settings were saved!'), self::RESULT_OK);
		}
	}