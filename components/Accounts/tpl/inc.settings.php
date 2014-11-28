<h2><?php echo self::l('Account'); ?></h2>
<div class="accounts settings">
	<h3><?php echo self::l('Personal'); ?></h3>
	<?php echo self::response('Accounts\Account'); ?>
	<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Account" />
		<input type="hidden" name="account[id]" value="<?php echo $account->getID(); ?>" />
		<table>
			<tr>
				<td><?php echo self::l('First name'); ?>:</td>
				<td><?php echo self::input('text', 'account[name_first]', $account->user_name_first); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Last name'); ?>:</td>
				<td><?php echo self::input('text', 'account[name_last]', $account->user_name_last); ?></td>
			</tr>
		</table>
		<input type="submit" value="<?php echo self::l('Save'); ?>" />
	</form>
</div>
<div class="accounts password">
	<h3><?php echo self::l('Password'); ?></h3>
	<?php echo self::response('Accounts\Password'); ?>
	<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\Password" />
		<table>
			<tr>
				<td><?php echo self::l('Current password'); ?>:</td>
				<td><?php echo self::input('password', 'current'); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('New password'); ?>:</td>
				<td><?php echo self::input('password', 'password1'); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Confirm'); ?>:</td>
				<td><?php echo self::input('password', 'password2'); ?></td>
			</tr>
		</table>
		<input type="submit" value="<?php echo self::l('Save'); ?>" />
	</form>
</div>