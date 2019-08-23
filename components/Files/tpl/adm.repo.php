<section class="content-header">
	<h1>
		<?php echo self::html($repo->getName()); ?>
		<small><?php echo \forge\Strings::byteSize($repo->getSize()); ?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo '/'.$page->page_url.'/Files'; ?>"><i class="fa fa-dashboard"></i> <?php echo self::l('Files'); ?></a></li>
		<li><?php echo self::html($repo->getName()); ?></li>
	</ol>
</section>
<section class="content container-fluid">
	<div class="box">
		<div class="box-header"><h3 class="box-title"><?php echo self::l('Files'); ?></h3></div>
		<div class="box-body">
			<table id="example1" class="table table-bordered table-striped table-hover dataTable" role="grid" aria-describedby="example1_info">
                <thead>
                <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 148px;">Rendering engine</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 183px;">Browser</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 162px;">Platform(s)</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 125px;">Engine version</th><th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 89px;">CSS grade</th></tr>
                </thead>
                <tbody>
                <tr role="row" class="odd">
                  <td class="sorting_1">Gecko</td>
                  <td>Firefox 1.0</td>
                  <td>Win 98+ / OSX.2+</td>
                  <td>1.7</td>
                  <td>A</td>
                </tr><tr role="row" class="even">
                  <td class="sorting_1">Gecko</td>
                  <td>Firefox 1.5</td>
                  <td>Win 98+ / OSX.2+</td>
                  <td>1.8</td>
                  <td>A</td>
                </tr><tr role="row" class="odd">
                  <td class="sorting_1">Gecko</td>
                  <td>Firefox 2.0</td>
                  <td>Win 98+ / OSX.2+</td>
                  <td>1.8</td>
                  <td>A</td>
                </tr><tr role="row" class="even">
                  <td class="sorting_1">Gecko</td>
                  <td>Firefox 3.0</td>
                  <td>Win 2k+ / OSX.3+</td>
                  <td>1.9</td>
                  <td>A</td>
                </tr><tr role="row" class="odd">
                  <td class="sorting_1">Gecko</td>
                  <td>Camino 1.0</td>
                  <td>OSX.2+</td>
                  <td>1.8</td>
                  <td>A</td>
                </tr><tr role="row" class="even">
                  <td class="sorting_1">Gecko</td>
                  <td>Camino 1.5</td>
                  <td>OSX.3+</td>
                  <td>1.8</td>
                  <td>A</td>
                </tr><tr role="row" class="odd">
                  <td class="sorting_1">Gecko</td>
                  <td>Netscape 7.2</td>
                  <td>Win 95+ / Mac OS 8.6-9.2</td>
                  <td>1.7</td>
                  <td>A</td>
                </tr><tr role="row" class="even">
                  <td class="sorting_1">Gecko</td>
                  <td>Netscape Browser 8</td>
                  <td>Win 98SE+</td>
                  <td>1.7</td>
                  <td>A</td>
                </tr><tr role="row" class="odd">
                  <td class="sorting_1">Gecko</td>
                  <td>Netscape Navigator 9</td>
                  <td>Win 98+ / OSX.2+</td>
                  <td>1.8</td>
                  <td>A</td>
                </tr><tr role="row" class="even">
                  <td class="sorting_1">Gecko</td>
                  <td>Mozilla 1.0</td>
                  <td>Win 95+ / OSX.1+</td>
                  <td>1</td>
                  <td>A</td>
                </tr></tbody>
              </table>
		</div>
	</div>
</section>











<h1><?php echo self::l('Files'); ?> <small><a href="/admin/Files"><?php echo $repo->getName(); ?></a></small></h1>
<div class="col-4-3"><div class="panel">
	<h1>/ <a href="?id=<?php echo $repo->getId(); ?>"><?php echo $repo->getName(); ?></a> / <?php if (!empty($_GET['path'])) {
			$folders = explode('/', $_GET['path']);
			$link = '?id='.$repo->getId().'&path=';
			foreach ($folders as $folder) {
				$link .= urlencode($folder.'/');
				echo '<a href="'.substr($link, 0, strlen($link)-3).'">'.self::html($folder).'</a> / ';
			}
		}
	?></h1>
	<?php echo self::response(['Files\Delete', 'Files\Rename', 'Files\Upload', 'Files\CreateFolder', 'Files\CreateFile']); ?>
	<?php
		echo $matrix->drawTable(
			array(
				'name' => self::l('Name'),
				'date' => self::l('Date'),
				'size' => self::l('Size'),
				'actions' => null
			),
			array(
				'name' => function($r) {
					$path = !empty($_GET['path']) ? \forge\Get::getString('path').'/' : '';
					$url = '?id='.\forge\Get::getInt('id').'&path='.urlencode($path.$r['name']);
					$icon = $r['type'] == 'dir' ? 'folder' : 'page';
					
					return '<a href="'.$url.'"><img src="/images/led/'.$icon.'.png" /> '.self::html($r['name']).'</a>';
				},
				'size' => function($r) {
					return is_null($r['size']) ? null : \forge\Strings::bytesize($r['size']);
				},
				'actions' => function($r) {
					$path = !empty($_GET['path']) ? \forge\Get::getString('path').'/' : '';
					$view = $r['type'] == 'dir' ? '?id='.\forge\Get::getInt('id').'&path='.urlencode($path.$r['name']) : '/files/'.self::html($path.$r['name']);
					$target = $r['type'] == 'dir' ? '_self' : '_blank';
					$out = '<a href="'.$view.'" target="'.$target.'"><img src="/images/led/magnifier.png" alt="'.self::l('View').'" title="'.self::l('View').'" /></a> ';
					$out .= '<a href="/admin/Files/edit?id='.$r['id'].'"><img src="/images/led/page_white_edit.png" alt="'.self::l('Edit').'" title="'.self::l('Edit').'" /></a> ';
					$out .= '<a href="javascript:rename(\''.self::html($r['name']).'\');"><img src="/images/led/pencil.png" alt="'.self::l('Rename').'" title="'.self::l('Rename').'" /></a> ';
					$out .= '<a href="javascript:trash(\''.self::html($path.$r['name']).'\');"><img src="/images/led/bin_closed.png" alt="'.self::l('Delete').'" title="'.self::l('Delete').'" /></a>';
					
					return $out;
				}
			)
		);
	?>
</div></div>
<div class="col-4">
	<div class="panel green">
		<h1><?php echo self::l('Upload'); ?></h1>
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