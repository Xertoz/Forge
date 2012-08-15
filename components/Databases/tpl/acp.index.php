<div class="admin databases list">
	<h1><?php echo _('Databases'); ?></h1>
	<?php echo self::response('Databases\Select'); ?>
	<table>
		<thead>
			<tr>
				<th><?php echo _('Driver'); ?></th>
				<th><?php echo _('Hostname'); ?></th>
				<th><?php echo _('Database'); ?></th>
				<th><?php echo _('Prefix'); ?></th>
				<th><?php echo _('Username'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($databases as $id => $config): ?>
			<tr id="<?php echo $id; ?>">
				<td><?php echo $config['driver']; ?></td>
				<td><?php echo $config['hostname']; ?></td>
				<td><?php echo $config['database']; ?></td>
				<td><?php echo $config['prefix']; ?></td>
				<td><?php echo $config['username']; ?></td>
				<td class="actions">
					<?php if ($default != $id): ?>
						<form action="/admin/Databases" method="POST">
							<input type="hidden" name="forge[controller]" value="Databases\Select" />
							<input type="hidden" name="id" value="<?php echo $id; ?>" />
							<input type="image" src="/images/led/connect.png" title="<?php echo _('Connect'); ?>" />
						</form>
						<form action="/admin/Databases" method="POST">
							<input type="hidden" name="forge[controller]" value="Databases\Delete" />
							<input type="hidden" name="id" value="<?php echo $id; ?>" />
							<input type="image" src="/images/led/cross.png" title="<?php echo _('Delete'); ?>" />
						</form>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<form action="/admin/Databases" method="POST">
		<input type="hidden" name="forge[controller]" value="Databases\Add" />
		<h2><?php echo _('New connection'); ?></h2>
		<?php echo self::response('Databases\Add'); ?>
		<table>
			<tr>
				<td width="100"><?php echo _('Driver'); ?>:</td>
				<td>
					<?php foreach ($drivers as $driver): ?>
						<?php echo self::input('radio', 'system', $driver, true, ['id' => $driver]); ?>
						<label for="<?php echo self::html($driver); ?>"><?php echo self::html($driver); ?></label>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo _('Hostname'); ?>:</td>
				<td><?php echo self::input('text', 'hostname'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Database'); ?>:</td>
				<td><?php echo self::input('text', 'database'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Prefix'); ?>:</td>
				<td><?php echo self::input('text', 'prefix'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Username'); ?>:</td>
				<td><?php echo self::input('text', 'username'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Password'); ?>:</td>
				<td><?php echo self::input('password', 'password'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="<?php echo _('Create'); ?>"></td>
			</tr>
		</table>
	</form>
</div>