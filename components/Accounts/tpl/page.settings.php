<div class="accounts settings">
	<h1><?php echo _('Settings'); ?></h1>
	<?php echo self::response('Accounts\Account'); ?>
	<form action="/user/settings" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Account" />
		<input type="hidden" name="account[id]" value="<?php echo $account->getID(); ?>" />
		<table>
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
	<?php echo self::response('Accounts\Password'); ?>
	<form action="/user/settings" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Password" />
		<table>
			<tr>
				<td><?php echo _('Current password'); ?>:</td>
				<td><?php echo self::input('password', 'current'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('New password'); ?>:</td>
				<td><?php echo self::input('password', 'password1'); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Confirm'); ?>:</td>
				<td><?php echo self::input('password', 'password2'); ?></td>
			</tr>
		</table>
		<input type="submit" value="<?php echo _('Save'); ?>" />
	</form>
</div>
<div class="accounts logout">
	<p><a href="/user/logout"><?php echo _('Log out'); ?></a></p>
</div>