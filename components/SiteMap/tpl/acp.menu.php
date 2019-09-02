<section class="content-header">
	<h1>
		<?=self::l('Pages')?>
	</h1>
	<ol class="breadcrumb">
		<li class="active"><i class="fa fa-files-o"></i> <?=self::l('Pages')?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<div class="col-md-9">
			<div class="box">
				<div class="box-header"><h3 class="box-title"><?=self::l('Site directory')?></h3></div>
				<div class="box-body">
					<div class="alert alert-danger hidden">
						<h4><i class="icon fa fa-warning"></i> <?=self::l('Error')?></h4>
						<p><?=self::l('The menu order could not be updated!')?></p>
					</div>
					<?=$pages->draw([
							'page_title' => self::l('Title'),
							'page_url' => self::l('URL')
						], [
							'page_title' => function($r) use(&$page) {
								return '<a href="/'.$page->page_url.'/SiteMap/page?id='.$r['forge_id'].'">'.self::html($r['page_title']).'</a>';
							},
							'page_url' => function($r) {
								return '<a href="/'.self::html($r['page_url']).'">/'.self::html($r['page_url']).'</a>';
							}
						], ['id' => 'forge-sitemap-menu', 'data-onrowreorder' => 'onRowReorder']
					)?>
				</div>
				<div class="overlay hidden"><i class="fa fa-refresh fa-spin"></i></div>
				<div class="box-footer">
					<span class="pull-right"><button type="button" class="btn btn-success" onclick="location = '/<?=$page->page_url?>/SiteMap/page';"><?php echo self::l('New page'); ?></button></span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="box">
				<div class="box-header"><h3 class="box-title"><?=self::l('Settings')?></h3></div>
				<div class="box-body">
					<form>
						<p>
							<?php echo self::l('Homepage'); ?>:
							<select>
								<option></option>
							</select>
						</p>
					</form>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary"><?php echo self::l('Save'); ?></button>
				</div>
			</div>
		</div>
	</div>
</section>
