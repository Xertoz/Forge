<table class="list<?php if ($matrix->isDraggable()): ?> sortable<?php endif; ?>"<?php foreach ($attributes as $attr => $value): ?>
	<?php echo $attr; ?>="<?php echo self::html($value); ?>"<?php endforeach; ?>>
	<thead>
		<tr>
			<?php foreach ($columns as $title): ?>
				<th><?php echo $title; ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rows as $row_key => $row): ?>
		<tr<?php if ($matrix->isDraggable()): ?> draggable="true"<?php endif; ?>>
				<?php foreach ($columns as $key => $title): ?>
					<?php if (isset($row[$key])): ?>
						<td class="<?php echo $key; ?>"><?php echo isset($stylize[$key]) ? $stylize[$key]($row, $items[$row_key]) : self::html($row[$key]); ?></td>
					<?php elseif (isset($stylize[$key])): ?>
						<td class="<?php echo $key; ?>"><?php echo $stylize[$key]($row, $items[$row_key]); ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="100%">
				<?php if ($matrix->getPage() > 1): ?>
					<a href="?<?php foreach ($_GET as $key => $value) if ($key != 'page') echo urlencode($key).'='.urlencode($value).'&'; ?>page=<?php echo $matrix->getPage()-1; ?>"><?php echo self::l('Previous'); ?></a>
				<?php endif; ?>
				<span><?php echo $matrix->getPage(); ?>/<?php echo $matrix->getPages(); ?></span>
				<?php if ($matrix->getPage() < $matrix->getPages()): ?>
					<a href="?<?php foreach ($_GET as $key => $value) if ($key != 'page') echo urlencode($key).'='.urlencode($value).'&'; ?>page=<?php echo $matrix->getPage()+1; ?>"><?php echo self::l('Next'); ?></a>
				<?php endif; ?>
			</th>
		</tr>
	</tfoot>
</table>
