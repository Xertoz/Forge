<?php
	/**
	* acp.Templates.php
	* Copyright 2010-2012 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Templates;

	class Admin implements \forge\components\Admin\Administration {
		/**
		* Show the connection list
		*/
		static public function index() {
			\forge\components\Accounts::Restrict('Templates','admin','list','r');

			$defaultTemplate = \forge\components\Templates::getTemplate();

			$tpl = array(
				'defaultTemplate' => $defaultTemplate,
				'templates' => \forge\components\Templates::getTemplates()
			);

			return \forge\components\Templates::display('components/Templates/tpl/acp.list.php',$tpl);
		}

		/**
		* View a specific template's info etc
		* @return string
		*/
		static public function view() {
			// Get the template info (or 404)
			try {
				$templates = \forge\components\Templates::getTemplates();
				$systemName = $_GET['name'];
				$template = $templates[$systemName];
				$defaultTemplate = \forge\components\Templates::getTemplate();
			}
			catch (\Exception $e) {
				throw new \forge\HttpException(_('The template does not exist'),\forge\HttpException::HTTP_NOT_FOUND);
			}

			return \forge\components\Templates::display(
				'components/Templates/tpl/acp.template.php',
				array(
					'defaultTemplate' => $defaultTemplate,
					'systemName' => $systemName,
					'template' => $template
				)
			);
		}
	}