<h1><?php echo self::l('Cron jobs'); ?></h1>
<div class="panel">
	<h1><?php echo self::l('Scheduled jobs'); ?></h1>
	<?php echo self::response('Cron\Run'); ?>
	<?php if (count($jobs)): ?>
	<table class="list">
		<thead>
			<tr>
				<th><?php echo self::l('Job'); ?></th>
				<th><?php echo self::l('Interval'); ?></th>
				<th><?php echo self::l('Last run'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($jobs as $job): ?>
			<tr>
				<td><?php echo self::html($job); ?></td>
				<td><?php echo $job::getCanonicalInterval(); ?></td>
				<td><?php echo date('Y-m-d H:i:s', \forge\components\Cron::getLastRun($job)); ?></td>
				<td class="actions">
					<form action="/admin/Cron" method="POST">
						<input type="hidden" name="forge[controller]" value="Cron\Run" />
						<input type="hidden" name="job" value="<?php echo self::html($job); ?>" />
						<input type="image" src="/images/led/arrow_redo.png" alt="<?php echo self::l('Run'); ?>" />
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p><i><?php echo self::l('No available cron jobs were found in your website.'); ?></i></p>
	<?php endif; ?>
</div>
<div class="panel orange">
	<h1><?php echo self::l('Log entries'); ?></h1>
	<p><i><?php echo self::l('No cron jobs have been executed yet'); ?></i></p>
</div>