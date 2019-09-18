{include file='header.tpl'}
<div class="identity login">
	<h1>{'Log in'|l}</h1>
	{foreach from=$forms item=form}
	<div class="provider">
		{$form}
	</div>
    {/foreach}
</div>
{include file='footer.tpl'}