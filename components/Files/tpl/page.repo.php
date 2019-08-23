<div>
	<ul>
		<?php foreach ($repo->getChildren() as $child): ?>
		<li>
			<img src="/images/led/<?php echo $child->isFolder() ? 'folder' : 'page'; ?>.png" />
			<a href="<?php echo $url.'/'.urlencode($child->getName()); ?>"><?php echo self::html($child->getName()); ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>