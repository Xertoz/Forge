<section class="content-header">
	<h1>
		<?=$entry->getId() ? self::l('Edit page') : self::l('New page')?>
		<small><?=self::html($entry->page_title)?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/<?=$page->page_url?>/SiteMap"><i class="fa fa-files-o"></i> <?=self::l('Pages')?></a></li>
		<li class="active"><?=$entry->getId() ? self::l('Edit page') : self::l('New page')?></li>
	</ol>
</section>
<section class="content container-fluid">
	<form action="/<?=$page->page_url?>/SiteMap/page" method="post">
		<?=self::input('hidden', 'forge[controller]', 'SiteMap\\Set')?>
		<?=self::input('hidden', 'page[id]', $entry->getId())?>
		<div class="row">
			<div class="col-md-4">
				<div class="box box-primary">
					<div class="box-header with-border"><h3 class="box-title"><?=self::l('Basic')?></h3></div>
					<div class="box-body">
						<div class="form-group">
							<label for="page-title"><?=self::l('Title')?></label>
							<?=self::input('text', 'page[title]', $entry->page_title, true, ['id' => 'page-title', 'class' => 'form-control', 'placeholder' => self::l('Name this page')])?>
						</div>
						<div class="form-group">
							<label for="page-type"><?=self::l('Type')?></label>
							<?=self::select('page[type]', $types, $entry->page_type, true, ['id' => 'page-type', 'class' => 'form-control'])?>
						</div>
						<div class="form-group">
							<label><?=self::input('checkbox', 'page[publish]', $entry->page_publish)?> <?=self::l('Publish to visitors')?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="box box-warning">
					<div class="box-header with-border"><h3 class="box-title"><?=self::l('Location')?></h3></div>
					<div class="box-body">
						<div class="form-group">
							<label for="page-parent"><?=self::l('Parent')?></label>
							<?=self::select('page[parent]', $parents, $entry->page_parent, true, ['id' => 'page-parent', 'class' => 'form-control'])?>
						</div>
						<div class="form-group">
							<label for="page-url"><?=self::l('URL')?></label>
							<?=self::input('text', 'page[url]', $entry->page_url, true, ['id' => 'page-url', 'class' => 'form-control'])?>
						</div>
						<div class="form-group">
							<label><?=self::input('checkbox', 'page[menu]', $entry->page_menu)?> <?=self::l('Display in the menu')?></label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="box box-danger">
					<div class="box-header with-border"><h3 class="box-title"><?=self::l('Advanced')?></h3></div>
					<div class="box-body">
						<div class="form-group">
							<label for="page-seo-description"><?=self::l('Description')?></label>
							<?=self::input('text', 'page[seo][meta_description]', $entry->meta_description, true, ['id' => 'page-seo-description', 'class' => 'form-control', 'placeholder' => self::l('Description for search engines'), 'maxlength' => 160])?>
						</div>
						<div class="form-group">
							<label for="page-seo-keywords"><?=self::l('Keywords')?></label>
							<?=self::input('text', 'page[seo][meta_keywords]', $entry->meta_keywords, true, ['id' => 'page-seo-description', 'class' => 'form-control', 'placeholder' => self::l('Keywords for search engines')])?>
						</div>
						<div class="form-group">
							<label><?=self::input('checkbox', 'page[default]', $entry->page_default)?> <?=self::l('Set as landing page')?></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($entry->getId()): echo $instance->getEditForm($entry->getId()); ?>
		<?php else: foreach ($typeInstances as $type): ?>
		<div id="<?=str_replace('\\','_',$type->getName())?>" class="plugin-form hidden">
			<?=$type->getCreationForm()?>
		</div>
		<?php endforeach; endif; ?>
		<?php if ($entry->getId()): ?>
		<button type="button" id="btn-delete" class="btn btn-danger"
				data-locale="<?=self::l('Are you sure you want to remove this page? This action is irreversible!')?>"
				data-error="<?=self::l('Unable to remove this page as an error occured!')?>"
				data-success="/<?=$page->page_url?>/SiteMap"
				data-id="<?=$entry->getId()?>">
			<?=self::l('Delete')?>
		</button>
		<?php endif; ?>
		<button type="submit" class="btn btn-success pull-right"><?=$entry->getId() ? self::l('Save') : self::l('Create')?></button>
	</form>
</section>