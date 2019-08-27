<?php
	/**
	* class.SiteMap.php
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
	class SiteMap extends \forge\components\SiteMap\Page {
		/**
		* Title
		* @var string
		*/
		protected $title = 'SEO: Site Map';

		/**
		* Search engine disabled
		* @var string
		*/
		const SEO_ENABLE = false;
		
		protected $dynamic = true;

		/**
		* View the page
		* @param \forge\components\SiteMap\db\Page Page
		* @param array Page vars
		* @return string
		* @throws Exception
		*/
		public function view($page, $vars) {
			switch ($vars['SUB_URI']) {
				default:
					throw new \forge\HttpException('Page not found', \forge\HttpException::HTTP_NOT_FOUND);
				
				case null:
					\forge\components\SiteMap::redirect('/'.$page->page_url.'/xml');
				
				case 'xml':
					return $this->makeXML();
				
				case 'xsl':
					return $this->makeXSL();
			}
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
				if (!constant($page->page_type.'::SEO_ENABLE'))
					continue;
				
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
			header('Content-type: text/xml;charset=UTF-8', true);
			return $xml->outputMemory();
		}

		/**
		* Generate sitemap.xsl
		* @return void
		*/
		protected function makeXSL() {
			header('Content-type: text/xsl;charset=UTF-8', true);
			return file_get_contents('components/SiteMap/tpl/sitemap.xsl');
		}
	}