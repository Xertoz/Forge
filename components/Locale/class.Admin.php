<?php
	/**
	* class.Admin.php
	* Copyright 2014 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Locale;

	/**
	* Administer locales
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function build() {
			\forge\components\Identity::restrict('com.Locale.Build');

			if (empty($_GET['locale']) || !\forge\components\Locale::isLocale($_GET['locale']))
				throw new \forge\HttpException(_('Locale not found'), \forge\HttpException::HTTP_NOT_FOUND);

			return \forge\components\Templates::display(
				'components/Locale/tpl/adm.build.php',
				['locale' => $_GET['locale']]
			);
		}
		static public function index() {
			\forge\components\Identity::restrict('com.Locale.Admin');

			return \forge\components\Templates::display(
				'components/Locale/tpl/adm.index.php',
				[
					'current' => \forge\components\Locale::getLocale(),
					'locales' => \forge\components\Locale\Library::getLocales()
				]
			);
		}

		static public function view() {
			\forge\components\Identity::restrict('com.Locale.Admin');

			if (empty($_GET['locale']) || !\forge\components\Locale::isLocale($_GET['locale']))
				throw new \forge\HttpException(_('Locale not found'), \forge\HttpException::HTTP_NOT_FOUND);

			$missing = isset($_GET['type']) && $_GET['type'] == 'missing';
			if ($missing) {
				$library = array_flip(\forge\components\Locale\Library::getMissing($_GET['locale']));
				$library = array_fill_keys(array_keys($library), '');
			}
			else
				$library = \forge\components\Locale\Library::getLocale($_GET['locale']);
			$rows = $columns = [];
			foreach ($library as $key => $value) {
				$rows[] = ['message' => $key, 'translation' => $value];
				$columns = ['message', 'translation'];
			}
			unset($library);

			return \forge\components\Templates::display(
				'components/Locale/tpl/adm.view.php',
				[
					'locale' => $_GET['locale'],
					'library' => new \forge\components\XML\ArrayMatrix($rows, $columns),
					'missing' => $missing
				]
			);
		}
	}