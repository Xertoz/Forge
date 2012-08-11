<div class="accounts confirmation">
	<?php if ($confirmed): ?>
		<h1><?php echo _('Thank You!'); ?></h1>
		<p><?php echo _('Your account has been registered. You must active it by clicking the link that will be sent to your email in a few moments.'); ?></p>
	<?php else: ?>
		<h1><?php echo _('Oops!'); ?></h1>
		<p><?php echo _('The account could not be activated!'); ?></p>
	<?php endif; ?>
</div>