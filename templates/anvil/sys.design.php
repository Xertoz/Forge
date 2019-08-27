<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo self::html(isset($page) ? $page->page_title : 'Forge '.FORGE_VERSION); ?></title>
		<link rel="stylesheet" type="text/css" href="/templates/anvil/design.css" />
		<?php echo self::header(); ?>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<ul>
					<?php foreach (\forge\components\SiteMap::getMenu() as $entry): ?>
						<li><a href="/<?php echo $entry->page_url; ?>"<?php if (isset($page) && $entry->getId() == $page->getId()):?> class="active"<?php endif; ?>><?php echo self::html($entry->page_title); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div id="content">
				<?php echo $content; ?>
			</div>
			<div id="footer">
				<span>
					<?php if (\forge\components\Identity::isAdmin()): ?>
						<a href="/admin">&raquo; <?php echo self::l('Dashboard'); ?></a>
					<?php endif; ?>
					<?php echo isset($page) ? $page->page_updated : date('Y-m-d H:i:s'); ?>
				</span>
			</div>
		</div>
	</body>
</html>
