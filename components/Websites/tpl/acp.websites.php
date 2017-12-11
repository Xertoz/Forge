<h1><?php echo self::l('Websites'); ?></h1>
<div class="panel">
	<h1><?php echo self::l('Handled'); ?></h1>
	<table class="list">
		<thead>
			<tr>
				<th><?php echo self::l('Domain'); ?></th>
				<th><?php echo self::l('Alias to'); ?></th>
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
						<a href="javascript://" onclick="if (confirm('<?php echo self::l('Do you really want to delete the website?'); ?>')) document.delete<?php echo $website->getId(); ?>.submit();"><img src="/images/led/cross.png" alt="<?php echo self::l('Delete'); ?>" title="<?php echo self::l('Delete'); ?>"></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div class="panel green">
	<h1><?php echo self::l('Add new'); ?></h1>
	<form action="/admin/Websites" method="post" name="create_website">
		<input type="hidden" name="forge[controller]" value="Websites\Create" />
		<table>
			<tr>
				<td width="100"><?php echo self::l('Hostname:'); ?></td>
				<td><input type="text" name="hostname" autocomplete="off" required="required" pattern="(\w+(\.?)|:)+" title="Name of the domain to host" /></td>
			</tr>
			<tr>
				<td><?php echo self::l('Alias of:'); ?></td>
				<td><input type="text" name="alias" autocomplete="off" pattern="(\w+(\.?)|:)+" /></td>
			</tr>
		</table>
		<p><input type="submit" value="<?php echo self::l('Add'); ?>" /></p>
	</form>
</div>