<div id="login">
	<h1><?php echo self::l('Administration'); ?></h1>
    <h2><?php echo 'Forge '.self::html(FORGE_VERSION); ?></h2>
	<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post">
		<?php echo self::response('Accounts\Login'); ?>
		<input type="hidden" name="forge[controller]" value="Accounts\Login" />
		<p>
			<label for="account"><?php echo self::l('Username'); ?>:</label>
			<?php echo self::input('text', 'account'); ?>
		</p>
		<p>
			<label for="password"><?php echo self::l('Password'); ?>:</label>
			<?php echo self::input('password', 'password'); ?><br />
			<label></label>
			<a id="lost-password" href="/user/lost-password"><?php echo self::l('Reset password'); ?></a>
		</p>
		<p>
            <input type="submit" value="<?php echo self::l('Log in'); ?>" onclick="return Login();">
        </p>
	</form>
</div>