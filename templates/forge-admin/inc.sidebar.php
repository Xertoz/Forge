		<aside class="main-sidebar">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img src="/templates/forge-admin/img/user2-160x160.jpg" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?php echo self::html($user->getName()); ?></p>
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>
				<ul class="sidebar-menu tree" data-widget="tree">
					<li class="header"><?php echo self::l('Administration'); ?></li>
					<?php foreach ($menu as $item) if ($item->getName() === 'dashboard') require 'inc.menu.php'; ?>
					<?php foreach ($menu as $item) if (!in_array($item->getName(), ['dashboard', 'developer', 'documentation'])) require 'inc.menu.php'; ?>
					<?php foreach ($menu as $item) if ($item->getName() === 'developer') require 'inc.menu.php'; ?>
					<?php foreach ($menu as $item) if ($item->getName() === 'documentation') require 'inc.menu.php'; ?>
				</ul>
			</section>
		</aside>
