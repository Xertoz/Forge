<div class="accounts lost-password">
	<h1><?php echo self::l('Lost password'); ?></h1>
	<p><?php echo self::l('If you have lost your password, we can send an email with instructions on how to reset it to you. Just fill in your email address here and follow the instructions we send!'); ?></p>
	<form action="/user/lost-password" method="POST">
		<input type="hidden" name="forge[controller]" value="Accounts\LostPassword" />
		<?php echo self::response('Accounts\LostPassword'); ?>
		<p><?php echo self::l('Email:'); ?> <?php echo self::input('text', 'email'); ?></p>
		<p><input type="submit" value="<?php echo self::l('Recover'); ?>" /></p>
	</form>
</div>