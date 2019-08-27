<section class="content-header">
	<h1>
		<?=$ref->getShortName()?>
		<small>Version <?php echo self::html($ref->getName()::getVersion()); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/<?php echo $page->page_url; ?>/Documentation"><i class="fa fa-book"></i> Documentation</a></li>
		<li><a href="/<?php echo $page->page_url; ?>/Documentation/modules"><?=self::l('Modules')?></a></li>
		<li class="active"><?=$ref->getShortName()?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<?php require 'inc.class.php'; ?>
		<?php require 'inc.menu.php'; ?>
	</div>
</section>