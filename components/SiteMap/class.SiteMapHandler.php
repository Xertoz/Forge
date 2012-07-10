<?php
	/**
	* view.SiteMap.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap;

	/**
	* The sitemap view
	*/
	class SiteMapHandler extends \forge\RequestHandler {
		/**
		* Initialize the request
		*/
		public function handle() {
			switch ($this->getPrefix()) {
				default:
					throw new \forge\HttpException('Page not found',\forge\HttpException::HTTP_NOT_FOUND);

				case 'robots.txt':
					$this->makeRobots();
				break;

				case 'sitemap':
					switch ($this->getPath()) {
						default:
							throw new \forge\HttpException('Page not found',\forge\HttpException::HTTP_NOT_FOUND);

						case null:
							\forge\components\SiteMap::redirect('/sitemap/xml');

						case 'xml':
							$this->makeXML();
						break;

						case 'xsl':
							$this->makeXSL();
						break;
					}
				break;
			}
		}

		/**
		* Generate robots.txt
		* @return void
		*/
		protected function makeRobots() {
			$this->setContentType('text/plain;charset=UTF-8');

			$output = "User-agent: *\n";
			$output .= "Disallow: /admin/\n";
			$output .= "Disallow: /xml/\n";
			$output .= 'Sitemap: http://'.$_SERVER['SERVER_NAME'].'/sitemap/xml';

			echo $output;
		}

		/**
		* Generate sitemap.xml
		* @return void
		*/
		protected function makeXML() {
			// We will be needing an XML writer
			$xml = new \XMLWriter();
			$xml->openMemory();
			$xml->startDocument('1.0','UTF-8');
			$xml->text('<?xml-stylesheet type="text/xsl" href="/sitemap/xsl"?>');

			// Start root element
			$xml->startElement('urlset');
			$xml->writeAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');

			// Get all pages & loop over them
			$pages = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
				'type' => new \forge\components\SiteMap\db\Page,
				'where' => array('page_publish'=>1)
			]));
			foreach ($pages as $page) {
				// Start page element
				$xml->startElement('url');

				// Write characteristics
				$xml->writeElement('loc','http://'.$_SERVER['SERVER_NAME'].'/'.$page->page_url);
				$xml->writeElement('priority',constant($page->page_type.'::SEO_PRIORITY'));
				$xml->writeElement('changefreq',constant($page->page_type.'::SEO_FREQUENCY'));
				$xml->writeElement('lastmod',substr($page->page_updated,0,10));

				// End page element
				$xml->endElement();

				// Append its children
				$type = new $page->page_type;
				foreach ($type->getChildren($page->getId()) as $child) {
					$xml->startElement('url');
					$xml->writeElement('loc','http://'.$_SERVER['SERVER_NAME'].'/'.$page->page_url.'/'.$child['uri']);
					$xml->writeElement('priority',constant($page->page_type.'::SEO_PRIORITY'));
					$xml->writeElement('changefreq',constant($page->page_type.'::SEO_FREQUENCY'));
					$xml->writeElement('lastmod',$child['updated']);
					$xml->endElement();
				}
			}

			// End root element
			$xml->endElement();

			// Output the XML
			$this->setContentType('text/xml;charset=UTF-8');
			echo $xml->outputMemory();
		}

		/**
		* Generate sitemap.xsl
		* @return void
		*/
		protected function makeXSL() {
			$this->setContentType('text/xsl;charset=UTF-8');
			echo file_get_contents('components/SiteMap/tpl/sitemap.xsl');
		}
	}