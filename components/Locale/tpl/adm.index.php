<h1><?php echo self::l('Locales'); ?></h1>
<?php echo self::response(['Locale\Create', 'Locale\Scan', 'Locale\Select']); ?>
<div class="col-3">
	<?php foreach ($locales as $locale): ?>
	<div class="panel">
		<h1><?php echo self::html($locale); ?></h1>
		<p>Includes <a href="/<?=$page->page_url?>/Locale/view?locale=<?php echo urlencode($locale); ?>"><?php echo \forge\components\Locale\Library::getEntries($locale); ?></a> messages.</p>
		<p>The locale is in need of <a href="/<?=$page->page_url?>/Locale/view?locale=<?php echo urlencode($locale); ?>&type=missing"><?php echo \forge\components\Locale\Library::getMissingEntries($locale); ?></a> translations.</p>
		<?php if ($locale != 'en_US'): ?>
		<p>
			<?php echo \forge\components\Locale\Library::isBuilt($locale) ? self::l('All messages have been built.') : self::l('A new build is required!'); ?>
			<?php if (\forge\components\Identity::hasPermission('com.Locale.Build')): ?>
			You may <a href="/<?=$page->page_url?>/Locale/build?locale=<?php echo urlencode($locale); ?>">build</a> this locale.
			<?php endif; ?>
		</p>
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>
<div class="col-3">
	<div class="panel green">
		<h1><?php echo self::l('Create new'); ?></h1>
		<?php if (\forge\components\Identity::hasPermission('com.Locale.Build')): ?>
			<form action="/<?=$page->page_url?>/Locale" method="post">
				<input type="hidden" name="forge[controller]" value="Locale\Create" />
				<p>
					<?php echo self::input('text', 'locale', null, true, ['placeholder' => 'en_US']); ?>
					<input type="submit" value="<?php echo self::l('Create'); ?>" />
				</p>
			</form>
		<?php endif; ?>
	</div>
	<div class="panel orange">
		<h1><?php echo self::l('Default'); ?></h1>
		<form action="/<?=$page->page_url?>/Locale" method="post">
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
	</div>
	<div class="panel yellow">
		<h1><?php echo self::l('Translate'); ?></h1>
		<?php if (\forge\components\Identity::hasPermission('com.Locale.Scan')): ?>
		<form action="/<?=$page->page_url?>/Locale" method="post">
			<input type="hidden" name="forge[controller]" value="Locale\Scan" />
			<p><input type="submit" value="<?php echo self::l('Scan en_US'); ?>" /></p>
		</form>
		<?php endif; ?>
	</div>
</div>
