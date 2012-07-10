<div class="admin databases list">
	<h1><?php echo _('Databases'); ?></h1>
	<p><?php echo _('Forge works against a database, wherein most data except files will be saved. You can select which database to work against, perform backups and maintenance here.'); ?></p>
	<p class="warning"><span><?php echo _('Warning'); ?>:</span> <?php echo _('You should not temper with these settings unless you do know what you are doing.'); ?></p>
	<table id="databases" class="tablesorter">
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
					<a<?php if ($default != $id): ?> href="javascript:selectConnection('<?php echo $id; ?>');"<?php endif; ?>><img src="/images/<?php if ($default == $id): ?>apply<?php else: ?>cancel<?php endif; ?>-16x16.png" alt="<?php echo _($default == $id ? 'Use' : 'Abandon'); ?>" title="<?php echo _($default == $id ? 'Use' : 'Abandon'); ?>"></a>
					<a href="javascript:deleteConnection('<?php echo $id; ?>');"><img src="/images/remove-16x16.png" title="<?php echo _('Delete'); ?>" alt="<?php echo _('Delete'); ?>"></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<form name="create" action="/xml/Databases/create" method="post">
		<table>
			<tr>
				<td><?php echo _('Driver'); ?>:</td>
				<td>
					<?php foreach ($drivers as $driver): ?>
						<input type="radio" name="newConnection[system]" value="<?php echo self::html($driver); ?>" id="<?php echo $id = uniqid(); ?>">
						<label for="<?php echo $id; ?>"><?php echo self::html($driver); ?></label>
					<?php endforeach; ?>
				</td>
				<td class="error" id="COM_DATABASES_NEW_NO_SYSTEM"><?php echo _('Choose a target system'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Hostname'); ?>:</td>
				<td><input type="text" name="newConnection[hostname]"></td>
				<td class="error" id="COM_DATABASES_NEW_NO_HOSTNAME"><?php echo _('Enter a hostname'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Database'); ?>:</td>
				<td><input type="text" name="newConnection[database]"></td>
				<td class="error" id="COM_DATABASES_NEW_NO_DATABASE"><?php echo _('Enter database name'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Prefix'); ?>:</td>
				<td><input type="text" name="newConnection[prefix]"></td>
				<td class="error" id="COM_DATABASES_NEW_NO_PREFIX"><?php echo _('Enter table prefix'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Username'); ?>:</td>
				<td><input type="text" name="newConnection[username]"></td>
				<td class="error" id="COM_DATABASES_NEW_NO_USERNAME"><?php echo _('Enter database username'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Password'); ?>:</td>
				<td><input type="password" name="newConnection[password]"></td>
				<td class="error" id="COM_DATABASES_NEW_NO_PASSWORD"><?php echo _('Enter database password'); ?></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="<?php echo _('Create'); ?>"></td>
			</tr>
		</table>
		<p class="error" id="COM_DATABASES_NEW_ACCESS_DENIED"><?php echo _('The database connection failed to set up.'); ?></p>
	</form>
</div>
<script type="text/javascript">
	function selectConnection(dbId) {
		if (!confirm('<?php echo _('Are you sure you want to switch main database connection? WARNING: The complete site will be reloaded'); ?>'))
			return;
		
		$.ajax({
			type: 'POST',
			url: '/xml/Databases/selectConnection',
			data: {
				ConnectionId: dbId
			},
			success: function(text,status) {
				window.location.reload();
			},
			error: function() {
				alert('<?php echo _('Main database connection could not be switched'); ?>');
			}
		});
	}
	
	function deleteConnection(dbId) {
		if (!confirm('<?php echo _('Are you sure you want to completely remove this database connection?'); ?>'))
			return;
		
		$.ajax({
			type: 'POST',
			url: '/xml/Databases/deleteConnection',
			data: {
				ConnectionId: dbId
			},
			success: function(text,status) {
				$('tr#'+dbId).remove();
			},
			error: function() {
				alert('<?php echo _('Database connection could not be removed'); ?>');
			}
		});
	}
	
	function form_create_success() {
		location.reload();
	}
</script>