<li class="{if $item->isActive()} active{/if}{if $item->hasChildren()} treeview{/if}"><a href="{if $item->getHREF()}{$item->getHREF()}{else}#{/if}">
	<i class="{$item->getIcon()}"></i>
	<span>{$item->getTitle()|escape}</span>
	{if $item->hasChildren()}
	<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	<ul class="treeview-menu">
		{foreach from=$item->getChildren() item=child}
		<li><a href="/{$page.page_url}/{$child->getHREF()}"><i class="fa fa-circle-o"></i>{$child->getTitle()|escape}</a></li>
		{/foreach}
	</ul>
	{/if}
</a></li>