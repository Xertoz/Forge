<div class="panel">
	<h1><?php echo self::l('Files'); ?></h1>
	<p><?php echo self::l('Browsing'); ?> /<a href="?path=">files</a>/<?php if (!empty($_GET['path'])) {
			$folders = explode('/', $_GET['path']);
			$link = '?path=';
			foreach ($folders as $folder) {
				$link .= urlencode($folder.'/');
				echo '<a href="'.substr($link, 0, strlen($link)-3).'">'.self::html($folder).'</a>/';
			}
		}
	?></p>
	<?php echo self::response('Files\Delete'); ?>
	<?php echo self::response('Files\Rename'); ?>
	<?php echo self::response('Files\Upload'); ?>
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
					$icon = $r['type'] == 'dir' ? 'folder' : 'page';
					
					return '<img src="/images/led/'.$icon.'.png" /> '.$r['name'];
				},
				'size' => function($r) {
					return is_null($r['size']) ? null : \forge\Strings::bytesize($r['size']);
				},
				'actions' => function($r) {
					$path = !empty($_GET['path']) ? $_GET['path'].'/' : '';
					$view = $r['type'] == 'dir' ? '?path='.urlencode($path.$r['name']) : '/files/'.self::html($path.$r['name']);
					$target = $r['type'] == 'dir' ? '_self' : '_blank';
					$out = '<a href="'.$view.'" target="'.$target.'"><img src="/images/led/magnifier.png" alt="'.self::l('View').'" title="'.self::l('View').'" /></a> ';
					$out .= '<a href="javascript:rename(\''.self::html($r['name']).'\');"><img src="/images/led/pencil.png" alt="'.self::l('Rename').'" title="'.self::l('Rename').'" /></a> ';
					$out .= '<a href="javascript:trash(\''.self::html($r['name']).'\');"><img src="/images/led/cross.png" alt="'.self::l('Delete').'" title="'.self::l('Delete').'" /></a>';
					
					return $out;
				}
			)
		);
	?>
	<form action="<?php echo self::html($_SERVER['REQUEST_URI']); ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="forge[controller]" value="Files\Upload" />
		<?php echo self::input('hidden', 'path', !empty($_GET['path']) ? $_GET['path'] : ''); ?>
		<p><?php echo self::l('Upload:'); ?> <input type="file" name="file" /></p>
		<p><input type="submit" value="<?php echo self::l('Upload'); ?>" /></p>
	</form>
</div>
<form name="rename" action="<?php echo isset($_GET['path']) ? '?path='.self::html($_GET['path']) : '/admin/Files'; ?>" method="POST">
	<input type="hidden" name="forge[controller]" value="Files\Rename" />
	<input type="hidden" name="source" value="" id="renameSource" />
	<input type="hidden" name="target" value="" id="renameTarget" />
</form>
<form name="trash" action="<?php echo isset($_GET['path']) ? '?path='.self::html($_GET['path']) : '/admin/Files'; ?>" method="POST">
	<input type="hidden" name="forge[controller]" value="Files\Delete" />
	<input type="hidden" name="file" value="" id="trashFile" />
</form>