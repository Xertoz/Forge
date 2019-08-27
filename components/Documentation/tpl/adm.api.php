<section class="content-header">
	<h1>
		<?php echo isset($class) ? $class : self::l('API'); ?>
		<small>Version <?php echo self::html(FORGE_VERSION); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/<?php echo $page->page_url; ?>/Documentation"><i class="fa fa-book"></i> Documentation</a></li>
		<?php if (isset($class)): ?>
		<li><a href="/<?php echo $page->page_url; ?>/Documentation/api">API</a></li>
		<li class="active"><?php echo $class; ?></li>
		<?php else: ?>
		<li class="active">API</li>
		<?php endif; ?>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<?php if (isset($classes)): ?>
		<div class="col-md-8">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?php echo self::l('API'); ?></h3>
				</div>
				<div class="box-body">
					<p><?php echo self::l('These are all the available classes in the <strong>\\forge\\</strong> namespace:'); ?></p>
					<p><ol>
						<?php foreach ($classes as $class): ?>
						<li><a href="?class=<?php echo $class; ?>"><?php echo $class; ?></a></li>
						<?php endforeach; ?>
					</ol></p>
				</div>
			</div>
		</div>
		<?php else: require 'inc.class.php'; endif; ?>
		<?php require 'inc.menu.php'; ?>
	</div>
</section>