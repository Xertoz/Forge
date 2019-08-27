<h1><?php echo self::l('People'); ?></h1>
<div class="panel">
	<h1><?php echo self::l('Accounts'); ?></h1>
	<table class="list">
		<thead>
			<tr>
				<th width="50%"><?php echo self::l('Name'); ?></th>
				<th><?php echo self::l('System'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($identities as /** @var \forge\components\Identity\Identity */ $identity): ?>
			<tr>
				<td><?php echo self::html($identity->getName()); ?></td>
				<td><?php echo self::html($identity->getTitle()); ?></td>
				<td class="actions">
					<a href="/admin/Identity/view?id=<?php echo $identity->getId(); ?>"><img src="/images/led/application_edit.png" alt="<?php echo self::l('Edit'); ?>" title="<?php echo self::l('Edit'); ?>" /></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>