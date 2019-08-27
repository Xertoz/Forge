<h1><?php echo self::l('Software'); ?></h1>
<?php echo self::response('Software\FixDatabase'); ?>
<div class="col-2">
	<div class="panel">
		<h1><?php echo self::l('Components'); ?></h1>
		<div class="components">
			<table class="list">
				<thead>
					<tr>
						<th><?php echo self::l('Component'); ?></th>
						<th width="75"><?php echo self::l('Configured'); ?></th>
						<th width="75"><?php echo self::l('Database'); ?></th>
						<th width="75"><?php echo self::l('Version'); ?></th>
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
									<img src="/images/led/accept.png" alt="<?php printf(self::l('%s was successfully configured'), self::html($component['name'])); ?>" title="<?php printf(self::l('%s was successfully configured'), self::html($component['name'])); ?>" />
								<?php elseif ($component['config'] === false): ?>
									<a href="/<?=$page->page_url?>/<?php echo self::html($component['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(self::l('%s hasn\'t been configured'), self::html($component['name'])); ?>" title="<?php printf(self::l('%s hasn\'t been configured'), self::html($component['name'])); ?>" /></a>
								<?php else: ?>
									<!-- NULL -->
								<?php endif; ?>
							</td>
							<td>
								<?php if ($component['database'] == -2 || $component['database'] == -1): ?>
									<!--  NULL -->
								<?php elseif ($component['database'] == 0): ?>
									<a href="/<?=$page->page_url?>/Software/fix?com=<?php echo self::html($component['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(self::l('The models in %s are invalid'), self::html($component['name'])); ?>" title="<?php printf(self::l('The models in %s are invalid'), self::html($component['name'])); ?>" /></a>
								<?php elseif ($component['database'] == 1): ?>
									<img src="/images/led/accept.png" alt="<?php printf(self::l('The models in %s are valid'), self::html($component['name'])); ?>" title="<?php printf(self::l('The models in %s are valid'), self::html($component['name'])); ?>" />
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
	</div>
	<div class="panel">
		<h1><?php echo self::l('Modules'); ?></h1>
		<div class="modules">
			<table class="list">
				<thead>
					<tr>
						<th><?php echo self::l('Module'); ?></th>
						<th width="75"><?php echo self::l('Configured'); ?></th>
						<th width="75"><?php echo self::l('Database'); ?></th>
						<th width="75"><?php echo self::l('Version'); ?></th>
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
									<img src="/images/led/accept.png" alt="<?php printf(self::l('%s was successfully configured'), self::html($module['name'])); ?>" title="<?php printf(self::l('%s was successfully configured'), self::html($module['name'])); ?>" />
								<?php elseif ($module['config'] === false): ?>
									<a href="/<?=$page->page_url?>/<?php echo self::html($module['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(self::l('%s hasn\'t been configured'), self::html($module['name'])); ?>" title="<?php printf(self::l('%s hasn\'t been configured'), self::html($module['name'])); ?>" /></a>
								<?php else: ?>
									<!-- NULL -->
								<?php endif; ?>
							</td>
							<td>
								<?php if ($module['database'] == -2 || $module['database'] == -1): ?>
									<!-- NULL -->
								<?php elseif ($module['database'] == 0): ?>
									<a href="/<?=$page->page_url?>/Software/fix?mod=<?php echo self::html($module['name']); ?>"><img src="/images/led/cross.png" alt="<?php printf(self::l('The models in %s are invalid'), self::html($module['name'])); ?>" title="<?php printf(self::l('The models in %s are invalid'), self::html($module['name'])); ?>" /></a>
								<?php elseif ($module['database'] == 1): ?>
									<img src="/images/led/accept.png" alt="<?php printf(self::l('The models in %s are valid'), self::html($module['name'])); ?>" title="<?php printf(self::l('The models in %s are valid'), self::html($module['name'])); ?>" />
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
</div>