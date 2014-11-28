<div class="panel">
	<h1><?php echo self::l('Locales'); ?></h1>
	<?php echo self::response('Locale\Create'); ?>
	<?php echo self::response('Locale\Scan'); ?>
	<?php echo self::response('Locale\Select'); ?>
	<?php if (\forge\components\Identity::hasPermission('com.Locale.Build')): ?>
		<form action="/admin/Locale" method="post">
			<input type="hidden" name="forge[controller]" value="Locale\Create" />
			<p>
				<?php echo self::input('text', 'locale', null, true, ['placeholder' => 'en_US']); ?>
				<input type="submit" value="<?php echo self::l('Create'); ?>" />
			</p>
		</form>
	<?php endif; ?>
	<form action="/admin/Locale" method="post">
		<input type="hidden" name="forge[controller]" value="Locale\Select" />
		<p>
			<select name="locale">
				<?php foreach ($locales as $locale): ?>
				<option value="<?php echo self::html($locale); ?>"<?php if ($current == $locale) echo ' selected="selected"'; ?>><?php echo self::html($locale); ?></option>
				<?php endforeach; ?>
			</select>
			<input type="submit" value="<?php echo self::l('Select'); ?>" />
		</p>
	</form>
	<?php if (\forge\components\Identity::hasPermission('com.Locale.Scan')): ?>
	<form action="/admin/Locale" method="post">
		<input type="hidden" name="forge[controller]" value="Locale\Scan" />
		<p><input type="submit" value="<?php echo self::l('Scan en_US'); ?>" /></p>
	</form>
	<?php endif; ?>
	<?php foreach ($locales as $locale): ?>
	<div class="locale">
		<h2><?php echo self::html($locale); ?></h2>
		<p>Includes <a href="/admin/Locale/view?locale=<?php echo urlencode($locale); ?>"><?php echo \forge\components\Locale\Library::getEntries($locale); ?></a> messages.</p>
		<p>The locale is in need of <a href="/admin/Locale/view?locale=<?php echo urlencode($locale); ?>&type=missing"><?php echo \forge\components\Locale\Library::getMissingEntries($locale); ?></a> translations.</p>
		<?php if ($locale != 'en_US'): ?>
		<p>
			<?php echo \forge\components\Locale\Library::isBuilt($locale) ? self::l('All messages have been built.') : self::l('A new build is required!'); ?>
			<?php if (\forge\components\Identity::hasPermission('com.Locale.Build')): ?>
			You may <a href="/admin/Locale/build?locale=<?php echo urlencode($locale); ?>">build</a> this locale.
			<?php endif; ?>
		</p>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>