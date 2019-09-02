<?php
	use forge\components\Databases\Table;
?><table
	<?php foreach ($attr as $key => $value): ?> <?=$key?>="<?=self::html($value)?>"<?php endforeach; ?>
	class="table table-bordered table-striped table-hover dataTable"
	role="grid"
	aria-describedby="example1_info"
	<?php if ($table->isDraggable()): ?> data-draggable<?php endif; ?>
	<?php if ($table->isPaging()): ?> data-paging<?php endif; ?>
	<?php if ($table->isSearchable()): ?> data-searchable<?php endif; ?>
	<?php if ($table->isSortable()): ?> data-sortable<?php endif; ?>>
	<thead>
		<tr role="row">
			<?php foreach ($columns as $column => $title): ?>
			<th <?php if (!is_null($title) && $table->isSortable()): ?>class="sorting"<?php endif; ?> tabindex="0">
				<?=$title?>
			</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($iterable as $item): ?>
		<tr<?php if ($item instanceof Table): ?> data-forge-id="<?=$item->getId()?>"<?php endif; ?>>
			<?php foreach ($columns as $column => $title): ?>
			<td<?php if (!is_null($title)): ?> tabindex="0"<?php endif; ?>><?=!isset($callbacks[$column])?self::html($item[$column]):$callbacks[$column]($item)?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
