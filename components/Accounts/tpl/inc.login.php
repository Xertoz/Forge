<h2><?php echo self::l('Account'); ?></h2>
<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post">
	<?php echo self::response('Accounts\Login'); ?>
	<input type="hidden" name="forge[controller]" value="Accounts\Login" />
	<p>
		<?php echo self::l('Account'); ?>:<br>
		<?php echo self::input('text', 'account'); ?>
	</p>
	<p>
		<?php echo self::l('Password'); ?>:<br>
		<?php echo self::input('password', 'password'); ?>
	</p>
	<p>
		<?php echo self::input('checkbox', 'cookie', null, true, ['id' => 'account_cookie']); ?>
		<label for="account_cookie"><?php echo self::l('Remember me'); ?></label>
	</p>
	<p><input type="submit" value="<?php echo self::l('Log in'); ?>" onclick="return Login();"> <a href="/user/register"><?php echo self::l('Register'); ?></a></p>
	<p><a href="/user/lost-password"><?php echo self::l('Lost your password?'); ?></a></p>
</form>