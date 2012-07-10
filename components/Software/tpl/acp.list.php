<div class="admin software">
	<h1><?php echo _('Software'); ?></h1>
	<div class="components">
		<?php if (\forge\Controller::getController() == 'Software\\FixDatabase' && \forge\Controller::getCode() == \forge\Controller::RESULT_BAD): ?>
			<p class="error"><?php echo \forge\Controller::getMessage(); ?></p>
		<?php endif; ?>
		<table class="software tablesorter">
			<thead>
				<tr>
					<th><?php echo _('Component'); ?></th>
					<th width="75"><?php echo _('Configured'); ?></th>
					<th width="75"><?php echo _('Database'); ?></th>
					<th width="75"><?php echo _('Version'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($components as $component): ?>
					<tr>
						<td>
							<?php echo self::html($component['name']); ?>
						</td>
						<td>
							<?php if ($component['config'] === true): ?>
								<img src="/images/led/accept.png" alt="<?php printf(_('%s was successfully configured'), self::html($component['name'])); ?>" title="<?php printf(_('%s was successfully configured'), self::html($component['name'])); ?>" />
							<?php elseif ($component['config'] === false): ?>
								<a href="/admin/<?php echo self::html($component['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(_('%s hasn\'t been configured'), self::html($component['name'])); ?>" title="<?php printf(_('%s hasn\'t been configured'), self::html($component['name'])); ?>" /></a>
							<?php else: ?>
								<!-- NULL -->
							<?php endif; ?>
						</td>
						<td>
							<?php if ($component['database'] == -2 || $component['database'] == -1): ?>
								<!--  NULL -->
							<?php elseif ($component['database'] == 0): ?>
								<a href="/admin/Software/fix?com=<?php echo self::html($component['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(_('The models in %s are invalid'), self::html($component['name'])); ?>" title="<?php printf(_('The models in %s are invalid'), self::html($component['name'])); ?>" /></a>
							<?php elseif ($component['database'] == 1): ?>
								<img src="/images/led/accept.png" alt="<?php printf(_('The models in %s are valid'), self::html($component['name'])); ?>" title="<?php printf(_('The models in %s are valid'), self::html($component['name'])); ?>" />
							<?php endif; ?>
						</td>
						<td>
							<?php if ($component['version']): ?>
								<?php echo $component['version']; ?>
							<?php else: ?>
								<!--  NULL -->
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="modules">
		<table class="software tablesorter">
			<thead>
				<tr>
					<th><?php echo _('Module'); ?></th>
					<th width="75"><?php echo _('Configured'); ?></th>
					<th width="75"><?php echo _('Database'); ?></th>
					<th width="75"><?php echo _('Version'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($modules as $module): ?>
					<tr>
						<td>
							<?php echo self::html($module['name']); ?>
						</td>
						<td>
							<?php if ($module['config'] === true): ?>
								<img src="/images/led/accept.png" alt="<?php printf(_('%s was successfully configured'), self::html($module['name'])); ?>" title="<?php printf(_('%s was successfully configured'), self::html($module['name'])); ?>" />
							<?php elseif ($module['config'] === false): ?>
								<a href="/admin/<?php echo self::html($module['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(_('%s hasn\'t been configured'), self::html($module['name'])); ?>" title="<?php printf(_('%s hasn\'t been configured'), self::html($module['name'])); ?>" /></a>
							<?php else: ?>
								<!-- NULL -->
							<?php endif; ?>
						</td>
						<td>
							<?php if ($module['database'] == -2 || $module['database'] == -1): ?>
								<!-- NULL -->
							<?php elseif ($module['database'] == 0): ?>
								<a href="/admin/Software/fix?mod=<?php echo self::html($module['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(_('The models in %s are invalid'), self::html($module['name'])); ?>" title="<?php printf(_('The models in %s are invalid'), self::html($module['name'])); ?>" /></a>
							<?php elseif ($module['database'] == 1): ?>
								<img src="/images/led/accept.png" alt="<?php printf(_('The models in %s are valid'), self::html($module['name'])); ?>" title="<?php printf(_('The models in %s are valid'), self::html($module['name'])); ?>" />
							<?php endif; ?>
						</td>
						<td>
							<?php if ($module['version']): ?>
								<?php echo $module['version']; ?>
							<?php else: ?>
								<!-- NULL -->
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>