<div class="accounts register">
	<h1><?php echo _('Register'); ?></h1>
	<form action="/user/register" method="POST">
		<table>
			<tr>
				<td><p><?php echo _('Account'); ?>:</p></td>
				<td><input type="text" name="account"></td>
			</tr>
			<tr>
				<td><p><?php echo _('Email'); ?>:</p></td>
				<td><input type="text" name="email"></td>
			</tr>
			<tr>
				<td><p><?php echo _('First name'); ?>:</p></td>
				<td><input type="text" name="name_first"></td>
			</tr>
			<tr>
				<td><p><?php echo _('Last name'); ?>:</p></td>
				<td><input type="text" name="name_last"></td>
			</tr>
			<tr>
				<td><p><?php echo _('Password'); ?>:</p></td>
				<td><input type="password" name="password"></td>
			</tr>
			<tr>
				<td><p><?php echo _('Confirm'); ?>:</p></td>
				<td><input type="password" name="password_confirm"></td>
			</tr>
		</table>
		<p><input type="submit" value="<?php echo _('Save'); ?>" /></p>
		<?php if ($exception): ?>
			<p class="error"><?php echo self::html($exception->getMessage()); ?></p>
		<?php endif; ?>
	</form>
</div>