<div class="panel">
	<h1><?php echo _('Update database'); ?></h1>
	<p><?php echo _('Carefully inspect the differences found between the Forge models and the current database.'); ?></p>
	<p><?php echo _('Be sure to make a backup of your data, since the automated fix is irreversible!'); ?></p>
	<h2><?php echo _('Differences'); ?></h2>
	<?php if (count($comparison)): ?>
		<?php foreach ($comparison as $model => $result): ?>
			<h3><?php echo self::html($model); ?></h3>
			<table class="list">
				<thead>
					<tr>
						<th width="50%"><?php echo _('Model'); ?></th>
						<th><?php echo _('Database'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><pre class="sql"><?php echo $result['control']; ?></pre></td>
						<td><pre class="sql"><?php echo $result['live']; ?></pre></td>
					</tr>
				</tbody>
			</table>
		<?php endforeach; ?>
	<?php else: ?>
		<p><?php echo _('No differences were found!'); ?></p>
	<?php endif; ?>
	<h2>Fix</h2>
	<p><?php echo _('You can have Forge to try and fix the differences automatically. It will do so by renaming and replacing the table.'); ?></p>
	<p style="color:red;"><?php echo _('Make sure you have a backup of the database before continuing!'); ?></p>
	<form action="/admin/Software" method="POST">
		<input type="hidden" name="forge[controller]" value="Software\FixDatabase" />
		<input type="hidden" name="name" value="<?php echo self::html($name); ?>" />
		<input type="hidden" name="type" value="<?php echo self::html($type); ?>" />
		<p><input type="submit" value="<?php echo _('Fix'); ?>" /></p>
	</form>
</div>