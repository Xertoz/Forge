<div class="accounts register">
	<h1><?php echo self::l('Register'); ?></h1>
	<form action="/user/register" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Register" />
		<?php echo self::response('Accounts\Register'); ?>
		<table>
			<tr>
				<td><p><?php echo self::l('Account'); ?>:</p></td>
				<td><?php echo self::input('text', 'account'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo self::l('Email'); ?>:</p></td>
				<td><?php echo self::input('text', 'email'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo self::l('First name'); ?>:</p></td>
				<td><?php echo self::input('text', 'name_first'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo self::l('Last name'); ?>:</p></td>
				<td><?php echo self::input('text', 'name_last'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo self::l('Password'); ?>:</p></td>
				<td><?php echo self::input('password', 'password'); ?></td>
			</tr>
			<tr>
				<td><p><?php echo self::l('Confirm'); ?>:</p></td>
				<td><?php echo self::input('password', 'password_confirm'); ?></td>
			</tr>
		</table>
		<p><input type="submit" value="<?php echo self::l('Register'); ?>" /></p>
	</form>
</div>