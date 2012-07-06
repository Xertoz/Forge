<div class="admin sitemap page">
    <h1><?php echo _($entry->getId() ? 'Edit' : 'Create'); ?></h1>
    <?php if (\forge\Controller::getCode() == \forge\Controller::RESULT_BAD): ?>
    	<p class="error"><?php echo self::html(\forge\Controller::getMessage()); ?></p>
    <?php endif; ?>
    <form action="/admin/SiteMap/page" method="post" name="page">
    	<input type="hidden" name="forge[controller]" value="SiteMap\Set" />
        <?php echo self::input('hidden', 'page[id]', $entry->getId()); ?>
        <fieldset>
            <legend><?php echo _('Settings'); ?></legend>
            <table>
                <tr>
                    <td><?php echo _('Title'); ?>:</td>
                    <td><?php echo self::input('text', 'page[title]', $entry->page_title)?></td>
                </tr>
                <tr>
                    <td><?php echo _('Type'); ?>:</td>
                    <td>
                        <select name="page[type]">
                            <option></option>
                            <?php foreach ($types as $index => $type): ?>
                                <option value="<?php echo self::html($type->getName()); ?>" id="type<?php echo $index; ?>"<?php if ($entry->page_type == $type->getName()): ?> selected="selected"<?php elseif ($entry->page_type): ?> disabled="disabled"<?php endif; ?>><?php echo self::html($type->getTitle()); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php echo _('Hierarchy'); ?></legend>
            <table>
                <tr>
                    <td><?php echo _('Parent'); ?>:</td>
                    <td>
                        <select name="page[hierarchy][parent]" onchange="determineUri();">
                            <option value="0"></option>
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo $page->getId(); ?>" title="<?php echo $page->page_url; ?>" value="<?php echo $page->getId(); ?>"<?php if($entry->page_parent == $page->getId()): ?> selected="selected"<?php endif; ?>><?php echo $page->page_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><?php echo _('URL'); ?>:</td>
                    <td><?php echo self::input('text', 'page[url]', $entry->page_url); ?></td>
                </tr>
                <tr>
                    <td><?php echo _('Publish'); ?>:</td>
                    <td><?php echo self::input('checkbox', 'page[publish]', 1, $entry->page_publish ? ['checked'=>'checked'] : array()); ?></td>
                </tr>
                <tr>
                    <td><?php echo _('Menu'); ?>:</td>
                    <td><?php echo self::input('checkbox', 'page[menu]', 1, $entry->page_menu ? ['checked'=>'checked'] : array()); ?></td>
                </tr>
                <tr>
                    <td><?php echo _('Default'); ?>:</td>
                    <td><?php echo self::input('checkbox', 'page[default]', 1, $entry->page_default ? ['checked'=>'checked'] : array()); ?></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend><?php echo _('Meta data'); ?></legend>
            <table>
                <tr>
                    <td><?php echo _('Description'); ?>:</td>
                    <td><input type="text" name="page[seo][meta_description]" maxlength="160" value="<?php echo self::html($entry->meta_description); ?>"></td>
                </tr>
                <tr>
                    <td><?php echo _('Keywords'); ?>:</td>
                    <td><input type="text" name="page[seo][meta_keywords]" value="<?php echo self::html($entry->meta_keywords); ?>"></td>
                </tr>
            </table>
        </fieldset>
        <?php if (!$entry->getId()): ?>
            <?php foreach ($types as $index => $type): ?>
            <div id="<?php echo str_replace('\\','_',$type->getName()); ?>" class="content">
                <?php echo $type->getCreationForm(); ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php echo $instance->getEditForm($entry->getId()); ?>
        <?php endif; ?>
        <p><input type="submit" value="<?php echo _($entry->getId() ? 'Edit' : 'Create'); ?>"></p>
    </form>
</div>