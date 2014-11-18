<div class="panel">
	<h1><?php echo _('Build locale'); ?></h1>
	<?php echo self::response('Locale\Build'); ?>
	<p><?php echo sprintf(_('You will build %s by clicking the button below.'), $locale); ?></p>
	<form action="/admin/Locale/build?locale=<?php echo urlencode($locale); ?>" method="post">
		<input type="hidden" name="forge[controller]" value="Locale\Build" />
		<input type="hidden" name="locale" value="<?php echo self::html($locale); ?>" />
		<p><input type="submit" value="<?php echo _('Build'); ?>" /></p>
	</form>
</div>