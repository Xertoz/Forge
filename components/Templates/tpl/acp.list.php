<div id="templates" class="panel">
	<h1><?php echo _('Templates'); ?></h1>
	<p><?php echo _('You may select which one of the installed templates to use.'); ?></p>
	<p><?php echo _('A template may not be eligible for use on your site depending on installed modules'); ?></p>
	<?php foreach ($templates as $system => $template): ?>
		<div class="template">
			<a href="/admin/Templates/view?name=<?php echo urlencode($system); ?>"><img src="/templates/<?php echo $system; ?>/template.png" alt="<?php echo self::html($template->getName()); ?>" title="<?php echo self::html($template->getName()); ?>" /></a>
		</div>
	<?php endforeach; ?>
	<div class="clear"></div>
</div>