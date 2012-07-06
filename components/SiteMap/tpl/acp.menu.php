<div class="admin sitemap menu">
    <h1><?php echo _('Pages'); ?></h1>
    <?php if (\forge\Controller::getCode() == \forge\Controller::RESULT_BAD): ?>
    	<p class="error"><?php echo self::html(\forge\Controller::getMessage()); ?></p>
    <?php endif; ?>
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
    		'actions' => function($r) {
    			$output = '<a href="/admin/SiteMap/page?id='.$r['forge_id'].'"><img src="/images/led/application_edit.png" alt="'._('Edit').'" title="'._('Edit').'" /></a>'.PHP_EOL;
				$output .= '<form action="/admin/SiteMap" method="POST">';
				$output .= '<input type="hidden" name="forge[controller]" value="SiteMap\Delete" />';
				$output .= self::input('hidden', 'page[id]', $r['forge_id'], false);
				$output .= '<input type="image" src="/images/led/cross.png" onclick="return confirm(\''.sprintf(_('Do you want to delete the page %s?\n\nThis action is irreversible.'), self::html($r['page_title'])).'\');" />';
				$output .= '</form>';
				return $output;
			}
		)
    ); ?>
    <a href="/admin/SiteMap/page"><img src="/images/led/add.png" alt="<?php echo _('New page'); ?>" title="<?php echo _('New page'); ?>"></a>
</div>