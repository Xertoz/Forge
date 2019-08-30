<section class="content-header">
	<h1>
		<?=self::html($repo->getName())?>
		<small><?=\forge\Strings::byteSize($repo->getSize())?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?='/'.$page->page_url.'/Files'?>"><i class="fa fa-dashboard"></i> <?=self::l('Files')?></a></li>
		<li><?=self::html($repo->getName())?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="row">
		<div class="col-md-9">
			<div class="box">
				<div class="box-header"><h3 class="box-title">
					/
					<a href="?id=<?=$repo->getId()?>"><?=$repo->getName()?></a>
					/
					<?php
						if (!empty($_GET['path'])) {
							$folders = explode('/', $_GET['path']);
							$link = '?id='.$repo->getId().'&path=';
							foreach ($folders as $folder) {
								$link .= urlencode($folder.'/');
								echo '<a href="'.substr($link, 0, strlen($link)-3).'">'.self::html($folder).'</a> / ';
							}
						}
					?>
				</h3></div>
				<div class="box-body">
					<?=$table->draw([
						'name' => self::l('Name'),
						'created' => self::l('Created'),
						'updated' => self::l('Modified'),
						'size' => self::l('Size'),
						'actions' => null
					], [
						'name' => function($item) use ($page, $path, $repo) {
							if (is_null($item->blob))
								$href = '?id='.$repo->getId().'&path='.urlencode((empty($path) ? '' : $path.'/').$item->name);
							else
								$href = '/'.$page->page_url.'/Files/file?id='.$item->getId();
							return '<a href="'.$href.'"><i class="ion ion-'.(!is_null($item->blob)?'document':'folder').'"></i> '.self::html($item->name).'</a>';
						},
						'size' => function($item) { return \forge\Strings::bytesize($item->size); },
						'actions' => function($item) use ($path, $repo) {
							$out = '<a href="javascript:rename(\''.self::html($item->name).'\');"><img src="/images/led/pencil.png" alt="'.self::l('Rename').'" title="'.self::l('Rename').'" /></a> ';
							$out .= '<a href="javascript:trash(\''.self::html($path.$item->name).'\');"><img src="/images/led/bin_closed.png" alt="'.self::l('Delete').'" title="'.self::l('Delete').'" /></a>';

							return $out;
						}
					])?>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="box">
				<div class="box-header"><h3 class="box-title"><?=self::l('Upload')?></h3></div>
				<div class="box-body">
					<div id="upload">
						<?php echo self::l('Drag files or click here!'); ?>
					</div>
					<div id="upload-progress">
						Uploading <span>?</span> files...
						<div class="progress"><div class="bar animated"></div></div>
					</div>
					<div id="upload-success">
						Success!
						<div class="progress"><div class="bar green animated"></div></div>
					</div>
					<div id="upload-fail">
						<?php echo self::l('The upload failed, try again!'); ?>
					</div>
					<form id="upload-form" method="post" enctype="multipart/form-data">
						<?php echo self::input('hidden', 'id', $repo->getId()); ?>
						<?php echo self::input('hidden', 'path', !empty($_GET['path']) ? $_GET['path'] : ''); ?>
						<p><?php echo self::l('File:'); ?> <input type="file" name="files[]" /></p>
						<p><input type="submit" value="<?php echo self::l('Upload'); ?>" /></p>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<!--
<div class="col-4">
	<div class="panel green">
		<h1><?php echo self::l('Create file'); ?></h1>
		<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post" id="files-create-file">
			<input type="hidden" name="forge[controller]" value="Files\CreateFile" />
			<?php echo self::input('hidden', 'id', $repo->getId()); ?>
			<?php echo self::input('hidden', 'path', !empty($_GET['path']) ? $_GET['path'] : ''); ?>
			<p><?php echo self::l('File name:'); ?> <input type="text" name="name" /></p>
		</form>
		<footer>
			<button type="submit" form="files-create-file"><?php echo self::l('Create file'); ?></button>
		</footer>
	</div>
	<div class="panel green">
		<h1><?php echo self::l('Create folder'); ?></h1>
		<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post" id="files-create-folder">
			<input type="hidden" name="forge[controller]" value="Files\CreateFolder" />
			<?php echo self::input('hidden', 'id', $repo->getId()); ?>
			<?php echo self::input('hidden', 'path', !empty($_GET['path']) ? $_GET['path'] : ''); ?>
			<p><?php echo self::l('Name:'); ?> <input type="text" name="name" /></p>
		</form>
		<footer>
			<button type="submit" form="files-create-folder"><?php echo self::l('Create folder'); ?></button>
		</footer>
	</div>
</div>
<form name="rename" action="<?php echo isset($_GET['path']) ? '?path='.self::html($_GET['path']) : '/admin/Files'; ?>" method="POST">
	<input type="hidden" name="forge[controller]" value="Files\Rename" />
	<input type="hidden" name="source" value="" id="renameSource" />
	<input type="hidden" name="target" value="" id="renameTarget" />
</form>
<form name="trash" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
	<input type="hidden" name="forge[controller]" value="Files\Delete" />
	<?php echo self::input('hidden', 'id', $repo->getId()); ?>
	<input type="hidden" name="file" value="<?php echo self::html(\forge\Get::getString('path', '')); ?>" id="trashFile" />
</form>
-->
