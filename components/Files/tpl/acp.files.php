<div class="admin files">
	<h1><?php echo _('Files'); ?></h1>
	<?php echo self::response('Files\Delete'); ?>
	<?php echo self::response('Files\Rename'); ?>
	<?php
		echo $matrix->drawTable(
			array(
				'name' => _('Name'),
				'date' => _('Date'),
				'size' => _('Size'),
				'actions' => null
			),
			array(
				'name' => function($r) {
					$icon = $r['type'] == 'dir' ? 'folder' : 'page';
					
					return '<img src="/images/led/'.$icon.'.png" /> '.$r['name'];
				},
				'size' => function($r) {
					return is_null($r['size']) ? null : \forge\String::bytesize($r['size']);
				},
				'actions' => function($r) {
					$view = $r['type'] == 'dir' ? '?path='.self::html($r['name']) : '/files/'.self::html($r['name']);
					$out = '<a href="'.$view.'"><img src="/images/led/magnifier.png" alt="'._('View').'" title="'._('View').'" /></a> ';
					$out .= '<a href="javascript:rename(\''.self::html($r['name']).'\');"><img src="/images/led/pencil.png" alt="'._('Rename').'" title="'._('Rename').'" /></a> ';
					$out .= '<a href="javascript:trash(\''.self::html($r['name']).'\');"><img src="/images/led/cross.png" alt="'._('Delete').'" title="'._('Delete').'" /></a>';
					
					return $out;
				}
			)
		);
	?>
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