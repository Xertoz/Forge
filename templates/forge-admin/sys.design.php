<?php
	self::addScriptFile('/templates/forge-admin/sys.design.js');

	$user = \forge\components\Identity::getIdentity();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo self::header(2); ?>
	</head>
	<body>
		<?php if (isset($menu)): /* Administration interface */ ?>
		<header>
			<h1><a href="/admin"><b>Forge</b> <?php echo self::html(FORGE_VERSION); ?></a></h1>
            <a href="javascript:menu.toggle();"><img src="/templates/forge-admin/img/menu.png" /></a>
			<div class="right">
				<a href="/admin/Identity/view?id=<?php echo $user->getId(); ?>"><?php echo $user->getName(); ?></a>
			</div>
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
		<script type="text/javascript">menu.init();</script>
		<div id="admin-content" class="<?php echo $css; ?>">
			<?php echo $content; ?>
		</div>
		<?php else: /* Login page, possibly? */ ?>
		<?php echo $content; ?>
		<?php endif; ?>
	</body>
</html>
