<?php
	/**
	* view.Page.php
	* Copyright 2011-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\SiteMap;

	/**
	* The view that accounts for administration requests.
	*/
	class PageHandler extends \forge\RequestHandler {
		/**
		* Page object
		* @var PageEntry
		*/
		protected $page;

		/**
		* URL
		* @var string
		*/
		protected $url;

		/**
		* Initialize the request
		*/
		public function handle() {
			$this->page = new \forge\components\SiteMap\db\Page();
			$this->url = $this->getPath();

			if (!$this->url) {
				try {
					$this->page->page_default = true;
					$this->page->select('page_default');
				}
				catch (\Exception $e) {
					throw new \forge\HttpException('Page not found',\forge\HttpException::HTTP_NOT_FOUND);
				}

				/**
				* Page plugin instance
				* @var Page
				*/
				$type = new $this->page->page_type;
			}
			else {
				$folders = explode('/',$this->url);

				for ($i=count($folders);!$this->page->getId() && $i>0;$i--) {
					$testuri = implode('/',array_slice($folders,0,$i));
					try {
						$this->page->page_url = $testuri;
						$this->page->select('page_url');

						/**
						* Page plugin instance
						* @var Page
						*/
						$type = new $this->page->page_type;

						if ($this->page->page_url != $this->url && !$type->isDynamic()) {
							$this->page = new \forge\components\SiteMap\db\Page();
							break;
						}
					}
					catch (\Exception $e) {
						continue;
					}
				}

				// If we found nothing here, first look into the history before telling 404
				if (!$this->page->getId()) {
					$history = new \forge\components\SiteMap\db\History();
					$history->url = $this->getPath();

					// Just tell the client 404 if we lack history
					try {
						$history->select('url');
					}
					catch (\Exception $e) {
						throw new \forge\HttpException('Page not found',\forge\HttpException::HTTP_NOT_FOUND);
					}

					// Are we going to redirect?
					if ($history->http == \forge\HttpException::HTTP_MOVED_PERMANENTLY)
						\forge\components\SiteMap::redirect($history->redirect,$history->http);

					// Otherwise, just throw a new exception
					throw new \forge\HttpException('Page not found',$history->http);
				}
			}

			\forge\components\Templates::setMeta(array(
				'description' => $this->page->meta_description,
				'keywords' => $this->page->meta_keywords,
				'language' => \forge\components\Locale::getLocale()
			));
			\forge\components\Templates::setVar('page',$this->page);
			\forge\components\Templates::setVar('type',$type);

			$uri = ($s = strpos($_SERVER['REQUEST_URI'],'?')) !== false ? substr($_SERVER['REQUEST_URI'],1,--$s) : substr($_SERVER['REQUEST_URI'],1);

			$this->setContentType('text/html;charset=UTF-8');
			echo $type->view($this->page->getId(),array(
				'REQ_URI' => $uri,
				'PAGE_URI' => $this->page->page_url,
				'SUB_URI' => substr($uri,1+strlen($this->page->page_url))
			));
		}
	}