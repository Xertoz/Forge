<section class="content-header">
	<h1>
		<?=self::l('Components')?>
		<small>Version <?php echo self::html(FORGE_VERSION); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/<?php echo $page->page_url; ?>/Documentation"><i class="fa fa-book"></i> Documentation</a></li>
		<li class="active"><?=self::l('Components')?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<div class="col-md-8">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=self::l('Introduction')?></h3>
				</div>
				<div class="box-body">
					<p><?=self::l('A component in Forge is a type of <a href="%s">addon</a> just like your own modules. The difference is that they are bundled together with the Forge installation and are not uninstallable.', '/'.$page->page_url.'/Documentation/api?class=Addon')?></p>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=self::l('Components')?></h3>
				</div>
				<div class="box-body">
					<p><?=self::l('These are all the available components in the <strong>\\forge\\components</strong> namespace:')?></p>
					<p><ol>
						<?php foreach ($components as $component): ?>
							<li><a href="/<?=$page->page_url?>/Documentation/component?component=<?=$component?>"><?=$component?></a></li>
						<?php endforeach; ?>
					</ol></p>
				</div>
			</div>
		</div>
		<?php require 'inc.menu.php'; ?>
	</div>
</section>