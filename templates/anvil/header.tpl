<!DOCTYPE html>
<html>
<head>
	{header}
	<link rel="stylesheet" type="text/css" href="/templates/anvil/design.css">
</head>
<body>
<div id="container">
	<div id="header">
		<ul>
			{foreach from=$forge.menu item=entry}
				<li><a href="/{$entry->page_url}"{if isset($page) && $entry->getId() == $page->getId()} class="active"{/if}>{$entry->page_title|escape}</a></li>
			{/foreach}
		</ul>
	</div>
	<div id="content">