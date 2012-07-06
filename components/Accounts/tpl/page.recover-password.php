<div class="accounts recover-password">
	<h1><?php echo _('Recover Password'); ?></h1>
	<?php if (empty($key)): ?>
		<p><?php echo _('We sent you a link to your email address - use it to recover your password!'); ?></p>
	<?php else: ?>
		<p><?php echo _('You can set a new password for your account by filling out the details below.'); ?></p>
		<table>
			<tr>
				<td width="100"><?php echo _('New password:'); ?></td>
				<td><input type="password" id="recover_passwd1" /></td>
			</tr>
			<tr>
				<td><?php echo _('Confirm:'); ?></td>
				<td><input type="password" id="recover_passwd2" /></td>
			</tr>
			<tr>
				<td><input type="button" id="accountsRecover" value="<?php echo _('Change'); ?>" onclick="recover();" /></td>
			</tr>
		</table>
	<?php endif; ?>
</div>