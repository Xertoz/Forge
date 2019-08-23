<h1><?php echo self::l('Profile'); ?> <small><?php echo self::html($identity->getName()); ?></small></h1>
<div class="col-2">
	<div class="panel">
		<h1><?php echo self::l('Profile picture'); ?></h1>
	</div>
	<?php foreach ($identity->getProviders() as $provider) echo $provider->showAdmin(); ?>
</div>
<div class="col-4">
	<div class="panel red">
		<h1><?php echo self::l('Permissions'); ?></h1>
		<form action="/admin/Identity/view?id=<?php echo (int)$_GET['id']; ?>" method="POST" id="form-permissions">
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
		</form>
		<footer>
			<button type="submit" form="form-permissions"><?php echo self::l('Save'); ?></button>
		</footer>
	</div>
</div>
<div class="col-4">
	<div class="panel yellow">
		<h1><?php echo self::l('Activity'); ?></h1>
        <table class="list">
            <?php foreach ($activity as $act): ?>
            <tr>
                <td><?php echo self::html($act['date']); ?></td>
                <td><?php echo self::html($act['message']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
	</div>
</div>