<?php
	/**
	* class.Robots.php
	* Copyright 2019 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/
	
	namespace forge\components\SiteMap\pages;

	/**
	* Display a robots.txt file to the client
	*/
	class Robots extends \forge\components\SiteMap\Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'SEO: robots.txt';

		/**
		* Search engine disabled
		* @var string
		*/
		const SEO_ENABLE = false;

		/**
		* View the page
		* @param \forge\components\SiteMap\db\Page Page
		* @param array Page vars
		* @return string
		* @throws Exception
		*/
		public function view($page, $vars) {
			header('Content-Type: text/plain;charset=UTF-8', true);

			$output = "User-agent: *\n";
			if (\forge\components\SiteMap::getRobots()) {
				$output .= "Disallow: /xml/\n";
				$output .= 'Sitemap: http://'.\forge\components\Websites::getDomain().'/sitemap/xml';
			}
			else
				$output .= 'Disallow: /';

			return $output;
		}
	}