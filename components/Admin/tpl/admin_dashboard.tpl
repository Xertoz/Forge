<section class="content-header">
	<h1>
		{'Dashboard'|l}
		<small>{'Version'|l} {FORGE_VERSION}</small>
	</h1>
</section>
<section class="content container-fluid">
	<div class="row">
		{foreach from=$infoboxes item=infobox}{$infobox}{/foreach}
	</div>
</section>