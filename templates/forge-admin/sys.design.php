<?php
	self::addScriptFile('/templates/forge-admin/sys.design.js');

	$user = \forge\components\Identity::getIdentity();
?><!DOCTYPE html>
<html>
	<head>
		<?=self::header()?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="icon" href="/images/led/blog.png">
		<link rel="stylesheet" href="/templates/forge-admin/css/bootstrap.min.css">
		<link rel="stylesheet" href="/templates/forge-admin/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" href="/templates/forge-admin/css/adminlte.min.css">
		<link rel="stylesheet" href="/templates/forge-admin/css/ionicons.min.css">
		<link rel="stylesheet" href="/templates/forge-admin/css/font-awesome.min.css">
		<link rel="stylesheet" href="/templates/forge-admin/css/skin-blue.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	</head>
	<body class="hold-transition skin-blue sidebar-mini<?php if (\forge\Memory::cookie('admin_menu') === 'false') echo ' sidebar-collapse'; ?>">
		<div class="wrapper">
			<?php if (isset($page)): ?>
			<?php require_once 'inc.navbar.php'; ?>
			<?php require_once 'inc.sidebar.php'; ?>
			<div class="content-wrapper" style="min-height: 1250px;">
				<?=$content?>
			</div>
			<footer class="main-footer">
				<div class="pull-right hidden-xs"><b><?php echo self::l('Version'); ?></b> <?php echo FORGE_VERSION; ?></div>
				<strong><?php echo self::l('Copyright'); ?> © 2009-2019 <a href="https://www.arosdigital.se/" target="_blank">Forge</a>.</strong>
				<?php echo self::l('All rights reserved.'); ?>
			</footer>
			<?php else: ?>
			<?php echo $content; ?>
			<?php endif; ?>
		</div>
		<script src="/script/jquery-3.3.1.min.js"></script>
		<script src="/templates/forge-admin/script/jquery.dataTables.min.js"></script>
		<script src="/templates/forge-admin/script/bootstrap.min.js"></script>
		<script src="/templates/forge-admin/script/adminlte.min.js"></script>
	</body>
</html>
