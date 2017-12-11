<h1><?php echo self::l('Robots'); ?></h1>
<?php echo self::response('SiteMap\Robots'); ?>
<div class="panel">
	<h1><?php echo self::l('Indexing'); ?></h1>
	<form action="/admin/SiteMap/robots" method="post">
		<input type="hidden" name="forge[controller]" value="SiteMap\Robots" />
		<p>Allow: <select name="enable">
			<option value="1"<?php if ($robots) echo ' selected="selected"'; ?>><?php echo self::l('Yes'); ?></option>
			<option value="0"<?php if (!$robots) echo ' selected="selected"'; ?>><?php echo self::l('No'); ?></option>
		</select></p>
		<p><input type="submit" value="<?php echo self::l('Save'); ?>" /></p>
	</form>
</div>