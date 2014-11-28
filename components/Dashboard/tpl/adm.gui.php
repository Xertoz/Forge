<div id="dashboard" class="panel">
	<h1><?php echo self::l('Dashboard'); ?></h1>
	<?php foreach ($infoboxes as $infobox): ?>
		<?php echo $infobox; ?>
	<?php endforeach; ?>
</div>