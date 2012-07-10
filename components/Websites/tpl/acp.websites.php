<div class="admin websites">
	<h1><?php echo _('Websites'); ?></h1>
	<p><?php echo _('The system handles all requests available to it, and as it may be requests made on multiple domains, it uses this table to determine what site to use.'); ?></p>
	<p><?php echo _('You may set up what web sites the system is supposed to handle and also its alias domains.'); ?></p>
	<table>
		<thead>
			<tr>
				<th><?php echo _('Domain'); ?></th>
				<th><?php echo _('Alias to'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($websites as $website): ?>
				<tr>
					<td><?php echo $website->domain; ?></td>
					<td><?php echo $website->alias; ?></td>
					<td class="actions">
						<form name="delete<?php echo $website->getId(); ?>" action="/admin/Websites" method="POST">
							<input type="hidden" name="forge[controller]" value="Websites\Delete" />
							<input type="hidden" name="id" value="<?php echo $website->getId(); ?>" />
						</form>
						<a href="javascript://" onclick="if (confirm('<?php echo _('Do you really want to delete the website?'); ?>')) document.delete<?php echo $website->getId(); ?>.submit();"><img src="/images/led/cross.png" alt="<?php echo _('Delete'); ?>" title="<?php echo _('Delete'); ?>"></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<h2><?php echo _('New website'); ?></h2>
	<form action="/admin/Websites" method="post" name="create_website">
		<input type="hidden" name="forge[controller]" value="Websites\Create" />
		<table>
			<tr>
				<td width="100"><?php echo _('Hostname:'); ?></td>
				<td><input type="text" name="hostname" autocomplete="off" required="required" pattern="(\w+(\.?)|:)+" title="Name of the domain to host" /></td>
			</tr>
			<tr>
				<td><?php echo _('Alias of:'); ?></td>
				<td><input type="text" name="alias" autocomplete="off" pattern="(\w+(\.?)|:)+" /></td>
			</tr>
		</table>
		<p><input type="submit" value="<?php echo _('Add'); ?>" /></p>
	</form>
</div>