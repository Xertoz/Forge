<?php
	use Michelf\Markdown;

	function phpdoc_text($cmt) {
		preg_match_all('/^\s*\* ?($|[^ \@\/].*)/m', $cmt, $matches);

		return Markdown::defaultTransform(implode(PHP_EOL, $matches[1]));
	}
?><div class="col-md-8">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo self::l('Description'); ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
					<i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<?=phpdoc_text($ref->getDocComment())?>
		</div>
	</div>
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title"><?php echo self::l('Synopsis'); ?></h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
					<i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body">
			<pre><code><?php require 'inc.code.php'; ?></code></pre>
		</div>
	</div>
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
			<p><?=phpdoc_text($ref->getMethod($method->name)->getDocComment())?></p>
			<?php if (count($method->getParameters())): ?>
			<p><ul>
				<?php foreach ($method->getParameters() as $param): ?>
				<li>
					<?php echo $param->getType() !== null ? $param->getType() : 'mixed'; ?>
					<strong>$<?php echo $param->name; ?></strong>
					<?php if ($param->isOptional() && $param->isDefaultValueAvailable()): ?>
					<small><?php echo $param->isDefaultValueConstant() ? $param->getDefaultValueConstantName() : (!is_array($param->getDefaultValue()) ?? $param->getDefaultValue()); ?></small>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul></p>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
</div>