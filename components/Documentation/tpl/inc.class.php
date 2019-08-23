<div class="col-md-8">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo self::l('Description'); ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
				<i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<p><?php echo self::html($ref->getDocComment()); ?></p>
		</div>
	</div>
	<?php if (count($ref->getMethods()) > 0): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo self::l('Methods'); ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
				<i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<p><ul>
				<?php foreach ($ref->getMethods() as $method) if ($method->isPublic()): ?>
				<li><a href="#<?php echo $method->name; ?>"><?php echo $method->name; ?></a></li>
				<?php endif; ?>
			</ul></p>
		</div>
	</div>
	<?php endif; ?>
	<?php if (count($ref->getConstants()) > 0): ?>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo self::l('Constants'); ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
				<i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<p><ul>
				<?php foreach ($ref->getConstants() as $constant => $value): ?>
				<li><a href="#"><?php echo $constant; ?></a> <small><?php echo self::html($value); ?></small></li>
				<?php endforeach; ?>
			</ul></p>
		</div>
	</div>
	<?php endif; ?>
	<?php foreach ($ref->getMethods() as $method) if ($method->isPublic()): ?>
	<a name="<?php echo $method->name; ?>"></a>
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo $method->name; ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
				<i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<p><?php echo self::html($ref->getMethod($method->name)->getDocComment()); ?></p>
			<?php if (count($method->getParameters())): ?>
			<p><ul>
				<?php foreach ($method->getParameters() as $param): ?>
				<li>
					<?php echo $param->getType() !== null ? $param->getType() : 'mixed'; ?>
					<strong>$<?php echo $param->name; ?></strong>
					<?php if ($param->isOptional()): ?>
					<small><?php echo $param->getDefaultValueConstantName(); ?></small>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul></p>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
</div>