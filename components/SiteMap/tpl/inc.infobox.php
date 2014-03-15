<?php if (\forge\components\Identity::hasPermission('com.SiteMap.Admin')): ?>
<div class="infobox pages" onclick="window.location = '/admin/SiteMap'">
	<img class="icon" src="/components/SiteMap/img/page.75x75.png" alt="<?php echo _('Pages'); ?>" title="<?php echo _('Pages'); ?>" />
	<h2><?php echo _('Pages');; ?>:</h2>
	<p><?php echo $pages; ?></p>
</div>
<?php endif; ?>
<?php if (\forge\components\Identity::hasPermission('com.SiteMap.Robots')): ?>
<div class="infobox pages" onclick="window.location = '/admin/SiteMap/robots'">
	<img class="icon" src="/components/SiteMap/img/robots.png" alt="<?php echo _('Robots'); ?>" title="<?php echo _('Robots'); ?>" />
	<h2><?php echo _('Robots');; ?>:</h2>
	<p><?php echo $robots ? _('On') : _('Off'); ?></p>
</div>
<?php endif; ?>