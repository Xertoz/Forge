<div class="accounts settings">
	<h1><?php echo _('Settings'); ?></h1>
	<?php echo self::response('Accounts\Account'); ?>
	<form action="/user/settings" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Account" />
		<input type="hidden" name="account[id]" value="<?php echo $account->getID(); ?>" />
		<table>
			<tr>
				<td><?php echo _('Account'); ?>:</td>
				<td><?php echo self::input('text', 'account[account]', $account->user_account); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Email'); ?>:</td>
				<td><?php echo self::input('text', 'account[email]', $account->user_email); ?></td>
			</tr>
			<tr>
				<td><?php echo _('First name'); ?>:</td>
				<td><?php echo self::input('text', 'account[name_first]', $account->user_name_first); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Last name'); ?>:</td>
				<td><?php echo self::input('text', 'account[name_last]', $account->user_name_last); ?></td>
			</tr>
		</table>
		<input type="submit" value="<?php echo _('Save'); ?>" />
	</form>
</div>
<div class="accounts password">
	<h1><?php echo _('Password'); ?></h1>
	<form action="/xml/Accounts/password" method="POST" onsubmit="return password(event.target);">
		<input type="hidden" name="account[id]" value="<?php echo $account->getID(); ?>" />
		<table>
			<tr>
				<td><?php echo _('Current password'); ?>:</td>
				<td><input type="password" name="account[password][old]"></td>
			</tr>
			<tr>
				<td><?php echo _('New password'); ?>:</td>
				<td><input type="password" name="account[password][new]"></td>
			</tr>
			<tr>
				<td><?php echo _('Confirm'); ?>:</td>
				<td><input type="password" name="account[password][confirm]"></td>
			</tr>
		</table>
		<input type="submit" value="<?php echo _('Save'); ?>" />
	</form>
</div>
<div class="accounts logout">
	<p><a href="/user/logout"><?php echo _('Log out'); ?></a></p>
</div>