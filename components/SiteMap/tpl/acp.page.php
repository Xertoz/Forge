<div class="panel">
	<h1><?php echo self::l($entry->getId() ? 'Edit' : 'Create'); ?></h1>
	<?php if (\forge\Controller::getCode() == \forge\Controller::RESULT_BAD): ?>
		<p class="error"><?php echo self::html(\forge\Controller::getMessage()); ?></p>
	<?php endif; ?>
	<form action="/admin/SiteMap/page" method="post" name="page">
		<input type="hidden" name="forge[controller]" value="SiteMap\Set" />
		<?php echo self::input('hidden', 'page[id]', $entry->getId()); ?>
		<h2><?php echo self::l('Settings'); ?></h2>
		<table>
			<tr>
				<td><?php echo self::l('Title'); ?>:</td>
				<td><?php echo self::input('text', 'page[title]', $entry->page_title, true, ['id' => 'page-title', 'onchange' => 'mkuri();']); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Type'); ?>:</td>
				<td>
					<select name="page[type]" onchange="display(this.options[this.selectedIndex].value.replace(/\\/g, '_'));">
						<option></option>
						<?php foreach ($types as $index => $type): ?>
							<option value="<?php echo self::html($type->getName()); ?>" id="type<?php echo $index; ?>"<?php if ($entry->page_type == $type->getName()): ?> selected="selected"<?php elseif ($entry->page_type): ?> disabled="disabled"<?php endif; ?>><?php echo self::html($type->getTitle()); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
		<h2><?php echo self::l('Hierarchy'); ?></h2>
		<table>
			<tr>
				<td><?php echo self::l('Parent'); ?>:</td>
				<td>
					<select name="page[parent]" onchange="determineUri();">
						<option value="0"></option>
						<?php foreach ($pages as $page): ?>
							<option value="<?php echo $page->getId(); ?>" title="<?php echo $page->page_url; ?>" value="<?php echo $page->getId(); ?>"<?php if($entry->page_parent == $page->getId()): ?> selected="selected"<?php endif; ?>><?php echo $page->page_title; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo self::l('URL'); ?>:</td>
				<td><?php echo self::input('text', 'page[url]', $entry->page_url, true, ['id' => 'page-url']); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Publish'); ?>:</td>
				<td><?php echo self::input('checkbox', 'page[publish]', 1, $entry->page_publish ? ['checked'=>'checked'] : array()); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Menu'); ?>:</td>
				<td><?php echo self::input('checkbox', 'page[menu]', 1, $entry->page_menu ? ['checked'=>'checked'] : array()); ?></td>
			</tr>
			<tr>
				<td><?php echo self::l('Default'); ?>:</td>
				<td><?php echo self::input('checkbox', 'page[default]', 1, $entry->page_default ? ['checked'=>'checked'] : array()); ?></td>
			</tr>
		</table>
		<h2><?php echo self::l('Meta data'); ?></h2>
		<table>
			<tr>
				<td><?php echo self::l('Description'); ?>:</td>
				<td><input type="text" name="page[seo][meta_description]" maxlength="160" value="<?php echo self::html($entry->meta_description); ?>"></td>
			</tr>
			<tr>
				<td><?php echo self::l('Keywords'); ?>:</td>
				<td><input type="text" name="page[seo][meta_keywords]" value="<?php echo self::html($entry->meta_keywords); ?>"></td>
			</tr>
		</table>
		<?php if (!$entry->getId()): ?>
			<?php foreach ($types as $index => $type): ?>
			<div id="<?php echo str_replace('\\','_',$type->getName()); ?>" class="plugin-form">
				<?php echo $type->getCreationForm(); ?>
			</div>
			<?php endforeach; ?>
		<?php else: ?>
			<?php echo $instance->getEditForm($entry->getId()); ?>
		<?php endif; ?>
		<p><input type="submit" value="<?php echo self::l($entry->getId() ? self::l('Save') : self::l('Create')); ?>"></p>
	</form>
</div>