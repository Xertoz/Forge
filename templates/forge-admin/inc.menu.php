<li class="<?php if ($item->isActive()) echo 'active'; if ($item->hasChildren()) echo ' treeview'; ?>"><a href="<?php echo $item->getHREF() ? $item->getHREF() : '#'; ?>">
	<i class="<?php echo $item->getIcon(); ?>"></i>
	<span><?php echo self::html($item->getTitle()); ?></span>
	<?php if ($item->hasChildren()): ?>
	<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
	<ul class="treeview-menu">
		<?php foreach ($item->getChildren() as $child): ?>
		<li><a href="/<?=$page->page_url?>/<?php echo $child->getHREF(); ?>"><i class="fa fa-circle-o"></i><?php echo self::html($child->getTitle()); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</a></li>