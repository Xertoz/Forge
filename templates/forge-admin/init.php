<?php
	use forge\components\Templates\Engine;

	Engine::addStyleFile('/vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css');
	Engine::addStyleFile('/vendor/almasaeed2010/adminlte/plugins/iCheck/all.css');
	Engine::addStyleFile('/vendor/datatables/datatables/media/css/dataTables.bootstrap.min.css');
	Engine::addStyleFile('/vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css');
	Engine::addStyleFile('/vendor/almasaeed2010/adminlte/bower_components/Ionicons/css/ionicons.min.css');
	Engine::addStyleFile('/vendor/almasaeed2010/adminlte/bower_components/font-awesome/css/font-awesome.min.css');
	Engine::addStyleFile('/vendor/almasaeed2010/adminlte/dist/css/skins/skin-blue.min.css');
	Engine::addStyleFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic');

	self::setVar('user',\forge\components\Identity::getIdentity());