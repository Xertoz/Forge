<section class="content-header">
	<h1>
		<?php echo self::l('Dashboard'); ?>
		<small>Version <?php echo self::html(FORGE_VERSION); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
		<li class="active">Here</li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<?php foreach ($infoboxes as $infobox) echo $infobox; ?>
	</div>
</section>