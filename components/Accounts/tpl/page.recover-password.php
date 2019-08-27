<div class="accounts recover-password">
	<h1><?php echo self::l('Recover Password'); ?></h1>
	<p><?php echo self::l('You can set a new password for your account by filling out the details below.'); ?></p>
	<form action="/user/recover-password?key=<?php echo urlencode($_GET['key']); ?>" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\RecoverPassword" />
		<?php echo self::input('hidden', 'key', $entry->key); ?>
		<?php echo self::response('Accounts\RecoverPassword'); ?>
		<table>
			<tr>
				<td width="100"><?php echo self::l('Password:'); ?></td>
				<td><?php echo self::input('password', 'password1'); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Confirm:'); ?></td>
				<td><?php echo self::input('password', 'password2'); ?></td>
			</tr>
			<tr>
				<td><input type="submit" value="<?php echo self::l('Change'); ?>" /></td>
			</tr>
		</table>
	</form>
</div>