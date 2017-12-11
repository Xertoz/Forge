<h1><?php echo self::l('Templates'); ?></h1>
<?php echo self::response('Templates\Set'); ?>
<div class="col-<?php echo count($templates) === 1 ? 1 : (count($templates) > 2 ? 3 : 2); ?>">
	<?php foreach ($templates as $system => $template): ?>
		<div class="panel<?php if ($system == $defaultTemplate) echo ' green'; ?>">
			<h1><?php echo self::html($template->getName()); ?></h1>
			<a href="/admin/Templates/view?name=<?php echo urlencode($system); ?>"><img src="/templates/<?php echo $system; ?>/template.png" alt="<?php echo self::html($template->getName()); ?>" title="<?php echo self::html($template->getName()); ?>" /></a>
		</div>
	<?php endforeach; ?>
</div>