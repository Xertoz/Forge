<?php if (\forge\components\Identity::hasPermission('com.SiteMap.Admin')): ?>
<div class="infobox pages" onclick="window.location = '/admin/SiteMap'">
	<img class="icon" src="/components/SiteMap/img/page.75x75.png" alt="<?php echo self::l('Pages'); ?>" title="<?php echo self::l('Pages'); ?>" />
	<h2><?php echo self::l('Pages');; ?>:</h2>
	<p><?php echo $pages; ?></p>
</div>
<?php endif; ?>
<?php if (\forge\components\Identity::hasPermission('com.SiteMap.Robots')): ?>
<div class="infobox pages" onclick="window.location = '/admin/SiteMap/robots'">
	<img class="icon" src="/components/SiteMap/img/robots.png" alt="<?php echo self::l('Robots'); ?>" title="<?php echo self::l('Robots'); ?>" />
	<h2><?php echo self::l('Robots');; ?>:</h2>
	<p><?php echo $robots ? self::l('On') : self::l('Off'); ?></p>
</div>
<?php endif; ?>