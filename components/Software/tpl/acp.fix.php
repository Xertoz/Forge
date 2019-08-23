<h1><?php echo self::l('Database builder'); ?> <small><?php echo self::html($name); ?></small></h1>
<div class="col-3-2">
	<?php foreach ($comparison as $model => $result): ?>
		<div class="panel orange">
			<h1><?php echo self::html($model); ?></h1>
			<table class="list">
				<thead>
					<tr>
						<th width="50%"><?php echo self::l('Model'); ?></th>
						<th><?php echo self::l('Database'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><pre class="sql"><?php echo $result['control']; ?></pre></td>
						<td><pre class="sql"><?php echo $result['live']; ?></pre></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php endforeach; ?>
</div>
<div class="col-3">
	<div class="panel red">
		<h1><?php echo self::l('Rebuild database'); ?></h1>
		<p><?php echo self::l('Carefully inspect the differences found between the Forge models and the current database!'); ?></p>
		<p style="color:red;"><?php echo self::l('Be sure to make a backup of your data, since the automated fix is irreversible!'); ?></p>
		<form action="/admin/Software" method="POST" id="rebuild-form">
			<input type="hidden" name="forge[controller]" value="Software\FixDatabase" />
			<input type="hidden" name="name" value="<?php echo self::html($name); ?>" />
			<input type="hidden" name="type" value="<?php echo self::html($type); ?>" />
		</form>
		<footer>
			<button type="submit" form="rebuild-form"><?php echo self::l('Rebuild'); ?></button>
		</footer>
	</div>
</div>