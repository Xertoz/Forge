<select <?php foreach ($attributes as $attr => $value) echo self::html($attr).'="'.self::html($value).'" '?>>
<?php foreach ($options as $value => $title): ?>
	<option value="<?php echo self::html($value); ?>"<?php if ($default == $value): ?> selected="selected"<?php endif; ?>>
		<?php echo self::html($title); ?>
	</option>
<?php endforeach; ?>
</select>