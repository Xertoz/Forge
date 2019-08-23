<h1><?php echo self::l('Pages'); ?></h1>
<div class="col-4-3">
    <div class="panel">
        <h1><?php echo self::l('Site structure'); ?></h1>
        <?php echo self::response('SiteMap\Delete'); ?>
        <?php echo self::response('SiteMap\Organize'); ?>
        <?php echo $pages->drawTable(
            array(
                'page_title' => self::l('Title'),
                'page_url' => self::l('URL'),
                'actions' => null
            ),
            array(
                'page_url' => function($r) {
                    return '<a href="/'.self::html($r['page_url']).'">/'.self::html($r['page_url']).'</a>';
                },
                'actions' => function($r, $item) use(&$page) {
                    $output = '<input type="hidden" name="menu[]" value="'.$r['forge_id'].'" class="forge-sitemap-menu-row" />';
                    if ($item->getChildren()->length())
                        $output .= '<a href="?parent='.$r['forge_id'].'"><img src="/images/led/find.png" alt="'.self::l('View children')." title=".self::l('View children').'" /></a> ';
                    $output .= '<button type="button" onclick="location =\'/'.$page->page_url.'/SiteMap/page?id='.$r['forge_id'].'\';">'.self::l('Edit').'</button>'.PHP_EOL;
                    $output .= '<form action="/'.$page->page_url.'/SiteMap" method="POST">';
                    $output .= '<input type="hidden" name="forge[controller]" value="SiteMap\Delete" />';
                    $output .= self::input('hidden', 'page[id]', $r['forge_id'], false);
                    $output .= '<button type="submit" onclick="return confirm(\''.sprintf(self::l('Do you want to delete the page %s?\n\nThis action is irreversible.'), self::html($r['page_title'])).'\');" class="red"'.($r['allowRemove'] ? '' : ' disabled').'>'.self::l('Delete').'</button>';
                    $output .= '</form>';
                    return $output;
                }
            ),
            array('id' => 'forge-sitemap-menu')
        ); ?>
        <form action="/<?=$page->page_url?>/SiteMap" method="POST" name="sitemap_menu"></form>
        <footer>
            <button type="button" onclick="location = '/<?=$page->page_url?>/SiteMap/page';"><?php echo self::l('New page'); ?></button>
            <button type="button" onclick="organize();" class="blue"><?php echo self::l('Save'); ?></button>
        </footer>
    </div>
</div>
<div class="col-4">
    <div class="panel green">
        <h1><?php echo self::l('Settings'); ?></h1>
        <form>
            <p>
                <?php echo self::l('Homepage'); ?>:
                <select>
                    <option></option>
                </select>
            </p>
        </form>
        <footer>
            <button type="submit" class="blue"><?php echo self::l('Save'); ?></button>
        </footer>
    </div>
</div>