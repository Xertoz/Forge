<?php

	use forge\components\Files\MimeType;

?>
<section class="content-header">
	<h1>
		<?=self::l('File')?>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?='/'.$page->page_url.'/Files'?>"><i class="fa fa-dashboard"></i> <?=self::l('Files')?></a></li>
		<li><a href="/<?=$page->page_url?>/Files/repo?id=<?=$repo->getId()?>"><?=self::html($repo->getName())?></a></li>
		<li class="active"><?=self::html($node->name)?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<div class="col-md-3">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=self::l('Properties')?></h3>
				</div>
				<div class="box-body">
					<strong><i class="fa fa-fw fa-font"></i> <?=self::l('Name')?></strong>
					<p class="text-muted"><?=self::html($node->name)?></p>
					<hr>
					<strong><i class="fa fa-fw fa-balance-scale"></i> <?=self::l('Size')?></strong>
					<p class="text-muted"><?=\forge\Strings::bytesize($node->size)?></p>
					<hr>
					<strong><i class="fa fa-fw fa-star"></i> <?=self::l('Created')?></strong>
					<p class="text-muted"><?=self::html($node->created)?></p>
					<?php if ($node->created !== $node->updated): ?>
						<hr>
						<strong><i class="fa fa-fw fa-repeat"></i> <?=self::l('Modified')?></strong>
						<p class="text-muted"><?=self::html($node->updated)?></p>
					<?php endif; ?>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title"><?=self::l('Tools')?></h3>
				</div>
				<div class="box-body">
					<p><?=self::l('Rename')?></p>
					<p><div class="input-group input-group-sm">
						<input type="text" class="form-control">
						<div class="input-group-btn">
							<button type="button" class="btn btn-warning"><?=self::l('Rename')?></button>
						</div>
					</div></p>
					<p><?=self::l('Delete')?></p>
					<p><div class="input-group input-group-sm">
						<div class="input-group-btn"><button type="button" class="btn btn-danger"><?=self::l('Delete')?></button></div>
					</div></p>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="box">
				<div class="box-header with-border">
					<h3 class="box-title"><?=self::l('Preview')?></h3>
				</div>
				<div class="box-body">
					<?php
						$href = '/';
						foreach ($parents as $parent)
							$href .= self::html($parent->name ?? $repo->getHREF()).'/';
						$href .= self::html($node->name);

						if (strstr($node->name, '.') !== false)
							switch ($type = MimeType::fromExtension($node->name)) {
								default:
									echo '<div class="alert alert-danger"><h4><i class="icon fa fa-warning"></i> '.self::l('Error!').'</h4><p>'.self::l('There\'s currently no way to preview a <b>%s</b> file type!', $type).'</p></div>';
									break;

								case 'image/jpeg':
									echo '<img src="'.$href.'" style="max-width:100%;">';
									break;
							}
					?>
				</div>
			</div>
		</div>
	</div>
</section>