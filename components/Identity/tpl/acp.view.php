<div class="admin accounts account">
	<h1><?php echo _('Identity'); ?></h1>
	<h2><?php echo _('Personal'); ?></h2>
	<table>
		<tr>
			<td><?php echo _('Name'); ?>:</td>
			<td><?php echo self::html($identity->getName()); ?></td>
		</tr>
		<tr>
			<td><?php echo _('Email'); ?>:</td>
			<td><?php echo self::html($identity->getEmail()); ?></td>
		</tr>
	</table>
	<h2><?php echo _('Permissions'); ?></h2>
	<form action="/admin/Identity/view?id=<?php echo (int)$_GET['id']; ?>" method="POST">
		<input type="hidden" name="forge[controller]" value="Identity\Permissions" />
		<input type="hidden" name="identity[id]" value="<?php echo (int)$_GET['id']; ?>" />
		<?php echo self::response('Identity\Permissions'); ?>
		<table class="matrix">
			<thead>
				<tr>
					<th><?php echo _('Name'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($permissions as $permission): ?>
				<tr>
					<td><?php echo self::html($permission); ?></td>
					<td class="actions permissive">
						<input type="hidden" name="permissions[<?php echo self::html($permission); ?>]" value="<?php echo $has = (int)$identity->hasPermission($permission); ?>" />
						<img src="/images/led/accept.png" alt="<?php echo _('Yes'); ?>" onclick="revoke(this);"<?php if (!$has): ?> style="display:none;"<?php endif; ?> />
						<img src="/images/led/cross.png" alt="<?php echo _('No'); ?>" onclick="grant(this);"<?php if ($has): ?> style="display:none;"<?php endif; ?> />
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p><input type="submit" value="<?php echo _('Save'); ?>" /></p>
	</form>
</div>