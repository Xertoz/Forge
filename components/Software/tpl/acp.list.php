<?php
	$columns = [
		'name' => self::l('Name'),
		'config' => self::l('Configured'),
		'database' => self::l('Database'),
		'version' => self::l('Version')
	];

	$values = [
		'config' => function($r) {
			$cls = [true => 'glyphicon glyphicon-ok text-success', false => 'glyphicon glyphicon-remove text-danger', -1 => ''];
			return '<span class="'.$cls[$r['config']].'"></span>';
		},
		'database' => function($r) {
			$cls = [true => 'glyphicon glyphicon-ok text-success', false => 'glyphicon glyphicon-remove text-danger', -1 => ''];
			return '<span class="'.$cls[$r['database']].'"></span>';
		}
	];
?><section class="content-header">
	<h1>
		<?=self::l('Software')?>
	</h1>
	<ol class="breadcrumb">
		<li class="active"><i class="fa fa-fw fa-rocket"></i> <?=self::l('Software')?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="nav-item active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><?=self::l('Modules')?></a></li>
			<li class="nav-item"><a href="#tab_2" data-toggle="tab" aria-expanded="false"><?=self::l('Components')?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
				<?=$modules->draw($columns, array_merge($values, ['name' => function($r) use($page) {
					return '<a href="/'.$page->page_url.'/Software/fix?mod='.$r['name'].'">'.$r['name'].'</a>';
				}]))?>
			</div>
			<div class="tab-pane" id="tab_2">
				<?=$components->draw($columns, array_merge($values, ['name' => function($r) use($page) {
					return '<a href="/'.$page->page_url.'/Software/fix?com='.$r['name'].'">'.$r['name'].'</a>';
				}]))?>
			</div>
		</div>
	</div>
</section>