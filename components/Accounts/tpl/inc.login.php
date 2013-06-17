<h2><?php echo _('Account'); ?></h2>
<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post">
	<?php echo self::response('Accounts\Login'); ?>
	<input type="hidden" name="forge[controller]" value="Accounts\Login" />
	<p>
		<?php echo _('Account'); ?>:<br>
		<?php echo self::input('text', 'account'); ?>
	</p>
	<p>
		<?php echo _('Password'); ?>:<br>
		<?php echo self::input('password', 'password'); ?>
	</p>
	<p>
		<?php echo self::input('checkbox', 'cookie', null, true, ['id' => 'account_cookie']); ?>
		<label for="account_cookie"><?php echo _('Remember me'); ?></label>
	</p>
	<p><input type="submit" value="<?php echo _('Log in'); ?>" onclick="return Login();"> <a href="/user/register"><?php echo _('Register'); ?></a></p>
	<p><a href="/user/lost-password"><?php echo _('Lost your password?'); ?></a></p>
</form>