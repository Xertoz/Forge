<?php
	use forge\components\Templates\Engine;

	Engine::addStyleFile('/templates/forge-admin/css/bootstrap.min.css');
	Engine::addStyleFile('/templates/forge-admin/css/icheck.min.css');
	Engine::addStyleFile('/templates/forge-admin/css/icheck-blue.css');
	Engine::addStyleFile('/vendor/datatables.net/datatables.min.css');
	Engine::addStyleFile('/templates/forge-admin/css/adminlte.min.css');
	Engine::addStyleFile('/templates/forge-admin/css/ionicons.min.css');
	Engine::addStyleFile('/templates/forge-admin/css/font-awesome.min.css');
	Engine::addStyleFile('/templates/forge-admin/css/skin-blue.min.css');
	Engine::addStyleFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic');

	self::setVar('user',\forge\components\Identity::getIdentity());