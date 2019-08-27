<div id="login">
	<h1><?php echo self::l('Administration'); ?></h1>
    <p><?php echo self::l('You must log in to access the administration console.'); ?></p>
	<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post" id="login-form">
		<?php echo self::response('Accounts\Login'); ?>
		<input type="hidden" name="forge[controller]" value="Accounts\Login" />
        <label for="account"><?php echo self::l('Account'); ?></label>
        <?php echo self::input('text', 'account'); ?>
        <label for="password"><?php echo self::l('Password'); ?></label>
        <?php echo self::input('password', 'password'); ?>
	</form>
    <footer>
        <button type="button" onclick="window.location = '/user/lost-password';" class="gray"><?php echo self::l('Reset password'); ?></button>
        <button type="submit" onclick="return Login();" form="login-form"><?php echo self::l('Log in'); ?></button>
    </footer>
</div>