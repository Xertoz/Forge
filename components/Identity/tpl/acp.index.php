<div class="admin identity list">
	<h1><?php echo _('People'); ?></h1>
	<table>
		<thead>
			<tr>
				<th width="50%"><?php echo _('Name'); ?></th>
				<th><?php echo _('System'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($identities as /** @var \forge\components\Identity\Identity */ $identity): ?>
			<tr>
				<td><?php echo self::html($identity->getName()); ?></td>
				<td><?php echo self::html($identity->getTitle()); ?></td>
				<td class="actions">
					<a href="/admin/Identity/view?id=<?php echo $identity->getId(); ?>"><img src="/images/led/application_edit.png" alt="<?php echo _('Edit'); ?>" title="<?php echo _('Edit'); ?>" /></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>