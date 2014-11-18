<!DOCTYPE html>
<html>
	<head>
		<?php echo self::header(2); ?>
	</head>
	<body>
		<header>
			<h1><a href="/admin">Forge</a></h1>
		</header>
		<nav>
			<ul>
				<?php foreach ($menu as $item): ?>
				<li>
					<span><?php echo self::html($item->getTitle()); ?></span>
					<?php if ($item->hasChildren()): ?>
					<ul>
						<?php foreach ($item->getChildren() as $child): ?>
						<li><a href="<?php echo $child->getHREF(); ?>"><?php echo self::html($child->getTitle()); ?></a></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<div id="admin-content" class="<?php echo $css; ?>">
			<?php echo $content; ?>
		</div>
	</body>
</html>
