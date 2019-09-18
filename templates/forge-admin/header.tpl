<!DOCTYPE html>
<html>
<head>
    {header tabs=2}
	<link href="/vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="/vendor/almasaeed2010/adminlte/plugins/iCheck/all.css" rel="stylesheet">
	<link href="/vendor/datatables/datatables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="/vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css" rel="stylesheet">
	<link href="/vendor/almasaeed2010/adminlte/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet">
	<link href="/vendor/almasaeed2010/adminlte/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="/vendor/almasaeed2010/adminlte/dist/css/skins/skin-blue.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic" rel="stylesheet">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="icon" href="/templates/forge-admin/img/blog.png">
	<script src="/templates/forge-admin/design.js"></script>
</head>
<body class="hold-transition {if $forge.ident}skin-blue sidebar-mini{else}login-page{/if}{if isset($smarty.cookies.admin_menu) && $smarty.cookies.admin_menu === 'false'} sidebar-collapse{/if}">
{if $forge.ident}
<div class="wrapper">
    {include file='navbar.tpl'}
    {include file='sidebar.tpl'}
	<div class="content-wrapper" style="min-height: 1250px;">
{/if}