<h2><?php echo _('Bind account'); ?></h2>
<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post">
	<?php echo self::response('Accounts\Bind'); ?>
	<input type="hidden" name="forge[controller]" value="Accounts\Bind" />
	<p>
		<?php echo _('Account'); ?>:<br>
		<?php echo self::input('text', 'account'); ?>
	</p>
	<p>
		<?php echo _('Password'); ?>:<br>
		<?php echo self::input('password', 'password'); ?>
	</p>
	<p><input type="submit" value="<?php echo _('Bind'); ?>"></p>
	<p><a href="/user/lost-password"><?php echo _('Lost your password?'); ?></a></p>
</form>