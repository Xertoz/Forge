<div class="admin templates template">
	<h1><?php echo self::html($template->getName()); ?></h1>
	<p><?php printf(_('This template was written by %s, copyright &copy; %s.'),$template->getAuthor(),$template->getCopyright()); ?></p>
	<?php if ($defaultTemplate == $systemName): ?>
		<p><?php echo _('The template is currently active.'); ?></p>
	<?php else: ?>
		<form action="/admin/Templates" method="POST">
			<input type="hidden" name="forge[controller]" value="Templates\Set" />
			<?php echo self::input('hidden', 'template', $systemName, false); ?>
			<p><input type="submit" value="<?php echo _('Select'); ?>" />
		</form>
	<?php endif; ?>
	<img src="/templates/<?php echo $systemName; ?>/template.png" alt="<?php echo self::html($template->getName()); ?>" title="<?php echo self::html($template->getName()); ?>" align="left" style="margin-right:15px;" />
	<table style="width:auto;margin-top:0;">
		<thead>
			<tr>
				<th><?php echo _('Supported modules'); ?> <abbr title="<?php echo _('Some modules require specific support from templates'); ?>">(?)</abbr></th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($template->getModules()) == 0): ?>
				<tr>
					<td><?php echo _('This template supports no special modules'); ?></td>
				</tr>
			<?php else: foreach($template->getModules() as $module): ?>
				<tr>
					<td><?php echo self::html($module); ?></td>
				</tr>
			<?php endforeach; endif; ?>
		</tbody>
	</table>
	<div class="clear"></div>
</div>