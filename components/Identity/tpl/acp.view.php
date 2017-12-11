<h1><?php echo self::l('Individual'); ?></h1>
<div class="panel">
	<h1><?php echo self::l('Personal information'); ?></h1>
	<table>
		<tr>
			<td><?php echo self::l('Name'); ?>:</td>
			<td><?php echo self::html($identity->getName()); ?></td>
		</tr>
		<tr>
			<td><?php echo self::l('Email'); ?>:</td>
			<td><?php echo self::html($identity->getEmail()); ?></td>
		</tr>
	</table>
</div>
<div class="panel red">
	<h1><?php echo self::l('Permissions'); ?></h1>
	<form action="/admin/Identity/view?id=<?php echo (int)$_GET['id']; ?>" method="POST">
		<input type="hidden" name="forge[controller]" value="Identity\Permissions" />
		<input type="hidden" name="identity[id]" value="<?php echo (int)$_GET['id']; ?>" />
		<?php echo self::response('Identity\Permissions'); ?>
		<table id="identity-permissions" class="list">
			<thead>
				<tr>
					<th><?php echo self::l('Name'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($permissions as $permission): ?>
				<tr>
					<td><?php echo self::html($permission); ?></td>
					<td class="actions permissive">
						<input type="hidden" name="permissions[<?php echo self::html($permission); ?>]" value="<?php echo $has = (int)$identity->hasPermission($permission); ?>" />
						<img src="/images/led/accept.png" alt="<?php echo self::l('Yes'); ?>" class="accept"<?php if (!$has): ?> style="display:none;"<?php endif; ?> />
						<img src="/images/led/cross.png" alt="<?php echo self::l('No'); ?>" class="deny"<?php if ($has): ?> style="display:none;"<?php endif; ?> />
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p><input type="submit" value="<?php echo self::l('Save'); ?>" /></p>
	</form>
</div>