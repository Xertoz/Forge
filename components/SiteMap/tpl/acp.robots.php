<div class="admin sitemap robots">
	<h1><?php echo _('Robots'); ?></h1>
	<?php echo self::response('SiteMap\Robots'); ?>
	<form action="/admin/SiteMap/robots" method="post">
		<input type="hidden" name="forge[controller]" value="SiteMap\Robots" />
		<p>Allow robots: <select name="enable">
			<option value="1"<?php if ($robots) echo ' selected="selected"'; ?>><?php echo _('Enabled'); ?></option>
			<option value="0"<?php if (!$robots) echo ' selected="selected"'; ?>><?php echo _('Disabled'); ?></option>
		</select></p>
		<p><input type="submit" value="<?php echo _('Save'); ?>" /></p>
	</form>
</div>