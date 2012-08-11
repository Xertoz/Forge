<div class="accounts register">
	<h1><?php echo _('Register'); ?></h1>
	<form action="/user/register" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Register" />
		<?php echo self::response('Accounts\Register'); ?>
		<table>
			<tr>
				<td><p><?php echo _('Account'); ?>:</p></td>
				<td><?php echo self::input('text', 'account'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo _('Email'); ?>:</p></td>
				<td><?php echo self::input('text', 'email'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo _('First name'); ?>:</p></td>
				<td><?php echo self::input('text', 'name_first'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo _('Last name'); ?>:</p></td>
				<td><?php echo self::input('text', 'name_last'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo _('Password'); ?>:</p></td>
				<td><?php echo self::input('password', 'password'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo _('Confirm'); ?>:</p></td>
				<td><?php echo self::input('password', 'password_confirm'); ?></td>
			</tr>
		</table>
		<p><input type="submit" value="<?php echo _('Save'); ?>" /></p>
	</form>
</div>