<h1><?php echo $entry->getId() ? self::l('Edit page').' <small>'.self::html($entry->page_title).'</small>' : self::l('New page'); ?></h1>
<?php echo self::response('SiteMap\\Set'); ?>
    <div class="col-3">
    <form action="/admin/SiteMap/page" method="post" name="page" id="sitemap-page">
        <input type="hidden" name="forge[controller]" value="SiteMap\Set" />
        <?php echo self::input('hidden', 'page[id]', $entry->getId()); ?>
        <div class="panel green">
            <h1><?php echo self::l('Basic'); ?></h1>
            <p>
                <?php echo self::l('Title'); ?>:
                <?php echo self::input('text', 'page[title]', $entry->page_title, true, ['id' => 'page-title', 'onchange' => 'mkuri();']); ?>
            </p>
            <p>
                <?php echo self::l('Type'); ?>:
                <select name="page[type]" onchange="display(this.options[this.selectedIndex].value.replace(/\\/g, '_'));">
                    <option<?php if ($entry->page_type): ?> disabled="disabled"<?php endif; ?>></option>
                    <?php foreach ($types as $index => $type): ?>
                        <option value="<?php echo self::html($type->getName()); ?>" id="type<?php echo $index; ?>"<?php if ($entry->page_type == $type->getName()): ?> selected="selected"<?php elseif ($entry->page_type): ?> disabled="disabled"<?php endif; ?>><?php echo self::html($type->getTitle()); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
        </div>
        <div class="panel orange">
            <h1><?php echo self::l('Navigation'); ?></h1>
            <p>
                <?php echo self::l('Parent'); ?>:
                <select name="page[parent]" onchange="determineUri();">
                    <option value="0"></option>
                    <?php foreach ($pages as $page): ?>
                        <option value="<?php echo $page->getId(); ?>" title="<?php echo $page->page_url; ?>" value="<?php echo $page->getId(); ?>"<?php if($entry->page_parent == $page->getId()): ?> selected="selected"<?php endif; ?><?php if($entry->getId() == $page->getId()): ?> disabled="disabled"<?php endif; ?>><?php echo $page->page_title; ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <?php echo self::l('URL'); ?>:
                <?php echo self::input('text', 'page[url]', $entry->page_url, true, ['id' => 'page-url']); ?>
            </p>
            <p>
                <?php echo self::l('Publish'); ?>:
                <?php echo self::input('checkbox', 'page[publish]', 1, $entry->page_publish ? ['checked'=>'checked'] : array()); ?>
            </p>
            <p>
                <?php echo self::l('Menu'); ?>:
                <?php echo self::input('checkbox', 'page[menu]', 1, $entry->page_menu ? ['checked'=>'checked'] : array()); ?>
            </p>
            <p>
                <?php echo self::l('Default'); ?>:
                <?php echo self::input('checkbox', 'page[default]', 1, $entry->page_default ? ['checked'=>'checked'] : array()); ?>
            </p>
        </div>
        <div class="panel red">
            <h1><?php echo self::l('Advanced'); ?></h1>
            <p>
                <?php echo self::l('Description'); ?>:
                <input type="text" name="page[seo][meta_description]" maxlength="160" value="<?php echo self::html($entry->meta_description); ?>">
            </p>
            <p>
                <?php echo self::l('Keywords'); ?>:
                <input type="text" name="page[seo][meta_keywords]" value="<?php echo self::html($entry->meta_keywords); ?>">
            </p>
        </div>
    </form>
</div>
<?php if (!$entry->getId()): ?>
    <?php foreach ($types as $index => $type): ?>
    <div id="<?php echo str_replace('\\','_',$type->getName()); ?>" class="plugin-form col-3-2">
        <?php echo $type->getCreationForm(); ?>
        <footer>
            <button type="submit" form="sitemap-page" class="blue"><?php echo self::l($entry->getId() ? self::l('Save') : self::l('Create')); ?></button>
        </footer>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-3-2">
        <?php echo $instance->getEditForm($entry->getId()); ?>
        <footer>
            <button type="submit" form="sitemap-page" class="blue"><?php echo self::l($entry->getId() ? self::l('Save') : self::l('Create')); ?></button>
        </footer>
    </div>
<?php endif; ?>