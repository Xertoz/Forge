<section class="content-header">
	<h1>
		<?php echo self::l('Files'); ?>
		<small><?php echo \forge\Strings::byteSize($size); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo '/'.$page->page_url.'/Files'; ?>"><i class="fa fa-dashboard"></i> <?php echo self::l('Files'); ?></a></li>
	</ol>
</section>
<section class="content container-fluid">
	<?php foreach ($repos as $repo): ?>
	<div class="col-md-3 col-sm-6 col-xs-12" style="cursor:pointer;" onclick="window.location = '/<?=$page->page_url?>/Files/repo?id=<?php echo $repo->getId(); ?>'">
		<div class="info-box">
			<span class="info-box-icon bg-blue"><i class="ion ion-folder"></i></span>
			<div class="info-box-content">
				<span class="info-box-text"><?php echo $repo->getName(); ?></span>
				<span class="info-box-number"><?php echo \forge\Strings::byteSize($repo->getSize()); ?></span>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="col-md-3">
		<div class="box box-default collapsed-box">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo self::l('Maintenance'); ?></h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
				</div>
			</div>
			<div class="box-body" style="">
				<button type="button" onclick="require('files-admin').runMaintenance();" class=""><?=self::l('Run')?></button>
			</div>
		</div>
	</div>
</section>