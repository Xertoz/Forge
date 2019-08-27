<div class="identity settings">
	<h1><?php echo self::l('Settings'); ?></h1>
	<?php foreach ($identity->getProviders() as $provider): ?>
	<div class="provider">
		<?php echo $provider->showSettings(); ?>
	</div>
	<?php endforeach; ?>
	<div class="bind">
		<?php foreach (\forge\components\Identity::getProviders() as $provider): ?>
		<?php if (!in_array($provider, $providers)): ?>
		<a href="/identity/bind?type=<?php echo urlencode($provider::getTitle()); ?>"><?php echo sprintf(self::l('Bind %s login'), self::html($provider::getTitle())); ?></a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<p class="clear"><a href="/identity/logout"><?php echo self::l('Log out'); ?></a></p>
</div>