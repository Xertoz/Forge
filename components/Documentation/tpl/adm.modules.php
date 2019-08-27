<section class="content-header">
	<h1>
		<?=self::l('Modules')?>
		<small>Version <?php echo self::html(FORGE_VERSION); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/<?php echo $page->page_url; ?>/Documentation"><i class="fa fa-book"></i> Documentation</a></li>
		<li class="active"><?=self::l('Modules')?></li>
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
					<p><?=self::l('A module in Forge is a type of <a href="%s">addon</a> just like the components. All modules are 3rd party software.', '/'.$page->page_url.'/Documentation/api?class=Addon')?></p>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=self::l('Modules')?></h3>
				</div>
				<div class="box-body">
					<p><?=self::l('These are all the available modules in the <strong>\\forge\\modules</strong> namespace:')?></p>
					<p><ol>
						<?php foreach ($modules as $module): ?>
							<li><a href="/<?=$page->page_url?>/Documentation/module?module=<?=$module?>"><?=$module?></a></li>
						<?php endforeach; ?>
					</ol></p>
				</div>
			</div>
		</div>
		<?php require 'inc.menu.php'; ?>
	</div>
</section>