<div class="panel">
	<h1><?php echo _('Pages'); ?></h1>
	<?php echo self::response('SiteMap\Delete'); ?>
	<?php echo self::response('SiteMap\Organize'); ?>
	<?php echo $pages->drawTable(
		array(
			'page_title' => _('Title'),
			'page_url' => _('URL'),
			'actions' => null
		),
		array(
			'page_url' => function($r) {
				return '<a href="/'.self::html($r['page_url']).'">/'.self::html($r['page_url']).'</a>';
			},
			'actions' => function($r, $item) {
				$output = '<input type="hidden" name="menu[]" value="'.$r['forge_id'].'" class="forge-sitemap-menu-row" />';
				if ($item->getChildren()->length())
					$output .= '<a href="?parent='.$r['forge_id'].'"><img src="/images/led/find.png" alt="'._('View children')." title="._('View children').'" /></a> ';
				$output .= '<a href="/admin/SiteMap/page?id='.$r['forge_id'].'"><img src="/images/led/application_edit.png" alt="'._('Edit').'" title="'._('Edit').'" /></a>'.PHP_EOL;
				$output .= '<form action="/admin/SiteMap" method="POST">';
				$output .= '<input type="hidden" name="forge[controller]" value="SiteMap\Delete" />';
				$output .= self::input('hidden', 'page[id]', $r['forge_id'], false);
				$output .= '<input type="image" src="/images/led/cross.png" onclick="return confirm(\''.sprintf(_('Do you want to delete the page %s?\n\nThis action is irreversible.'), self::html($r['page_title'])).'\');" />';
				$output .= '</form>';
				return $output;
			}
		),
		array('id' => 'forge-sitemap-menu')
	); ?>
	<form action="/admin/SiteMap" method="POST" name="sitemap_menu"></form>
	<a href="javascript:organize();"><img src="/images/led/accept.png" title="<?php echo _('Save'); ?>" alt="<?php echo _('Save'); ?>" /></a>
	<a href="/admin/SiteMap/page"><img src="/images/led/add.png" alt="<?php echo _('New page'); ?>" title="<?php echo _('New page'); ?>"></a>
</div>