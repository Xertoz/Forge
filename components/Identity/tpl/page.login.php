<div class="identity login">
	<h1><?php echo _('Log in'); ?></h1>
	<?php foreach ($providers as $provider): try { $login = $provider::showLogin(); ?>
	<div class="provider">
		<?php echo $login; ?>
	</div>
	<?php } catch(\Exception $e) {} endforeach; ?>
</div>