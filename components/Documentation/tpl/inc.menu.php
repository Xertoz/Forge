<div class="col-md-4">
	<div class="box">
		<div class="box-header with-border"><h3 class="box-title"><?php echo self::l('Index'); ?></h3></div>
		<div class="box-body">
			<ol>
				<li><a href="/<?php echo $page->page_url; ?>/Documentation">About Forge</a></li>
			</ol>
		</div>
	</div>
	<div class="box">
		<div class="box-header with-border"><h3 class="box-title"><?php echo self::l('Code'); ?></h3></div>
		<div class="box-body">
			<ol>
				<li><a href="/<?php echo $page->page_url; ?>/Documentation/api">API</a></li>
				<li>
					<a href="/<?php echo $page->page_url; ?>/Documentation/components">Components</a>
					<ol>
						<?php foreach (\forge\Addon::getComponents() as $component): ?>
						<li><a href="/<?php echo $page->page_url; ?>/Documentation/component?component=<?=$component?>"><?=$component?></a></li>
						<?php endforeach; ?>
					</ol>
				</li>
				<li>
					<a href="/<?php echo $page->page_url; ?>/Documentation/modules">Modules</a>
					<ol>
						<?php foreach (\forge\Addon::getModules() as $module): ?>
							<li><a href="/<?php echo $page->page_url; ?>/Documentation/module?module=<?=$module?>"><?=$module?></a></li>
						<?php endforeach; ?>
					</ol>
				</li>
				<li><a href="/<?php echo $page->page_url; ?>/Documentation/api">Tools</a></li>
			</ol>
		</div>
	</div>
</div>