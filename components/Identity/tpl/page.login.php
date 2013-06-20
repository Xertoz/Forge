<div class="identity login">
	<h1><?php echo _('Log in'); ?></h1>
	<?php foreach ($forms as $form): ?>
	<div class="provider">
		<?php echo $form; ?>
	</div>
	<?php endforeach; ?>
</div>