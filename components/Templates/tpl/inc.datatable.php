<table <?php if (isset($attr['id'])): ?>id="<?=$attr['id']?>"<?php endif; ?> class="table table-bordered table-striped table-hover dataTable" role="grid" aria-describedby="example1_info">
	<thead>
		<tr role="row">
			<?php foreach ($columns as $column => $title): ?>
			<th <?php if (!is_null($title)): ?>class="<?=$column===array_key_first($columns)?'sorting_asc':'sorting'?>"<?php endif; ?> tabindex="0" aria-controls="example1" aria-sort="ascending" aria-label="?">
				<?=$title?>
			</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($iterable as $item): ?>
		<tr>
			<?php foreach ($columns as $column => $title): ?>
			<td<?php if (!is_null($title)): ?> tabindex="0"<?php endif; ?>><?=!isset($callbacks[$column])?self::html($item[$column]):$callbacks[$column]($item)?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
