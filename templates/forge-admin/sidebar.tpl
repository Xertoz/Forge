<aside class="main-sidebar">
	<section class="sidebar">
		<div class="user-panel">
			<div class="pull-left image">
				<img src="/templates/forge-admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p>{$ident->getName()|escape}</p>
				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		<form action="#" method="get" class="sidebar-form">
			<div class="input-group">
				<input type="text" name="q" class="form-control" placeholder="Search...">
				<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
							</button>
						</span>
			</div>
		</form>
		<ul class="sidebar-menu tree" data-widget="tree">
			<li class="header">{'Administration'|l}</li>
			{foreach from=$menu item=item}{if $item->getName() === 'dashboard'}{include file='menu.tpl'}{/if}{/foreach}
            {foreach from=$menu item=item}{if !in_array($item->getName(), ['dashboard', 'developer', 'documentation'])}{include file='menu.tpl'}{/if}{/foreach}
            {foreach from=$menu item=item}{if $item->getName() === 'developer'}{include file='menu.tpl'}{/if}{/foreach}
            {foreach from=$menu item=item}{if $item->getName() === 'documentation'}{include file='menu.tpl'}{/if}{/foreach}
		</ul>
	</section>
</aside>
