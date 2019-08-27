<div class="accounts confirmation">
	<h1><?php echo self::l('Activation'); ?></h1>
	<?php if ($confirmed): ?>
	<p><?php echo self::l('The account has successfully been activated. You may now use it to log in on our community.'); ?></p>
	<?php else: ?>
	<p><?php echo self::l('Your account could not be activated!'); ?>
	<?php endif; ?>
</div>