<?php
	/**
	* class.Admin.php
	* Copyright 2010-2013 Mattias Lindholm
	*
	* This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
	* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
	* to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
	*/

	namespace forge\components\Software;

	use forge\components\Templates\DataTable;

	/**
	* Software component of Forge 4
	* Administration interface
	*/
	class Admin implements \forge\components\Admin\Administration {
		static public function fix() {
			$comparison = array();
			$controller = 'forge\\'.(isset($_GET['com']) ? 'components\\'.$_GET['com'] : 'modules\\'.$_GET['mod']);
			
			// Instantiate models so all dynamic classes are generated
			$models = $controller::getNamespace('db');
			foreach ($models as $model)
				new $model;
			
			// Loop all models
			$models = $controller::getNamespace('db');
			foreach ($models as $model) {
				if (!$model::isHandled())
					continue;
				
				$object = new $model;
				$live = $object->getCreate();
				$control = $object->buildCreate();

				if ($live != $control)
					$comparison[$model] = ['live' => $live, 'control' => $control];
			}

			return \forge\components\Templates::display('components/Software/tpl/acp.fix.php',
				array(
					'comparison' => $comparison,
					'name' => isset($_GET['com']) ? $_GET['com'] : $_GET['mod'],
					'type' => isset($_GET['com']) ? 'COM' : 'MOD'
				)
			);
		}

		static public function index() {
			\forge\components\Identity::restrict('com.Software.Admin');

			// Read components & modules
			$components = \forge\Addon::getComponents();
			$modules = \forge\Addon::getModules();

			// Get some more info about them
			foreach ($components as &$component)
				$component = \forge\components\Software::getComponentStatus($component);
			foreach ($modules as &$module)
				$module = \forge\components\Software::getModuleStatus($module);


			// Show window.
			return \forge\components\Templates::display('components/Software/tpl/acp.list.php', [
				'components' => new DataTable($components),
				'modules' => new DataTable($modules)
			]);
		}
	}