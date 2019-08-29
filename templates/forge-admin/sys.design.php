<!DOCTYPE html>
<html>
	<head>
		<?=self::header(2)?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="icon" href="/templates/forge-admin/img/blog.png">
		<script src="/templates/forge-admin/sys.design.js"></script>
	</head>
	<body class="hold-transition <?php if (isset($page)): ?>skin-blue sidebar-mini<?php else: ?>login-page<?php endif; if (\forge\Memory::cookie('admin_menu') === 'false') echo ' sidebar-collapse'; ?>">
		<?php if (isset($page)): ?>
		<div class="wrapper">
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
		</div>
		<?php else: ?>
		<?php echo $content; ?>
		<?php endif; ?>
	</body>
</html>
