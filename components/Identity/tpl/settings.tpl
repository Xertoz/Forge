{include file='header.tpl'}
<div class="identity settings">
	<h1>{'Settings'|l}</h1>
	{foreach from=$identity->getProviders() item=provider}
		<div class="provider">
			{$provider->showSettings()}
		</div>
    {/foreach}
	<div class="bind">
		{foreach from=$providers item=provider}{if !in_array($provider, $identity->getProviders())}
			<a href="/{$page->page_url}/bind?type={$provider::getTitle()|escape:'url'}">{'Bind %s login'|l|replace:'%s':$provider::getTitle()}</a>
		{/if}{/foreach}
		<?php foreach (\forge\components\Identity::getProviders() as $provider): ?>
		<?php if (!in_array($provider, $providers)): ?>
		<a href="/identity/bind?type=<?php echo urlencode($provider::getTitle()); ?>"><?php echo sprintf(self::l('Bind %s login'), self::html($provider::getTitle())); ?></a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<p class="clear"><a href="/identity/logout"><?php echo self::l('Log out'); ?></a></p>
</div>
{include file='footer.tpl'}