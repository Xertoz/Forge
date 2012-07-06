<div class="accounts lost-password">
	<h1><?php echo _('Lost password'); ?></h1>
	<p><?php echo _('If you have lost your password, we can send an email with instructions on how to reset it to you. Just fill in your email address here and follow the instructions we send!'); ?></p>
	<p><?php echo _('Email:'); ?> <input type="text" id="lost_password_email" /></p>
	<p><input type="button" value="<?php echo _('Recover'); ?>" id="accountsRecover" onclick="recover();" /></p>
</div>