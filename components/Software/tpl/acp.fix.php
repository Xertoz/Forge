<section class="content-header">
	<h1>
		<?=self::l('Database builder')?>
		<small><?php echo self::html($name); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/<?=$page->page_url?>/Software"><i class="fa fa-fw fa-rocket"></i> <?=self::l('Software')?></a></li>
		<li class="active"><?=self::html($name)?></li>
	</ol>
</section>
<section class="content container-fluid">
	<?php foreach ($comparison as $model => $result): ?>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-success">
				<div class="box-header"><h3 class="box-title"><?=self::html($model)?></h3></div>
				<div class="box-body"><pre class="code"><?=$result['control']?></pre></div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-danger">
				<div class="box-header"><h3 class="box-title"><?=self::html($model)?></h3></div>
				<div class="box-body"><pre class="code"><?=$result['live']?></pre></div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="box box-warning">
		<div class="box-header"><h3 class="box-title"><?=self::l('Rebuild database')?></h3></div>
		<div class="box-body">
			<p><?=self::l('Carefully inspect the differences found between the Forge models and the current database!')?></p>
			<p style="color:red;"><?=self::l('Be sure to make a backup of your data, since the automated fix is irreversible!')?></p>
			<form action="/<?=$page->page_url?>/Software" method="POST" id="rebuild-form">
				<input type="hidden" name="forge[controller]" value="Software\FixDatabase" />
				<input type="hidden" name="name" value="<?=self::html($name)?>" />
				<input type="hidden" name="type" value="<?=self::html($type)?>" />
				<button type="submit" form="rebuild-form" class="form-validate"><?=self::l('Rebuild')?></button>
			</form>
		</div>
	</div>
</section>