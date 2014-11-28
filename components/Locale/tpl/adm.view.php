<div class="panel">
	<h1><?php echo self::html($locale); ?></h1>
	<p><?php if ($missing) echo self::l('Displaying messages that require translation.'); else echo self::l('Displaying installed messages.'); ?></p>
	<?php echo $library->drawTable(['message' => self::l('Message'), 'translation' => self::l('Translation')], [], ['id' => 'messages']); ?>
</div>