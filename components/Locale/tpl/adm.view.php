<div class="admin locale view">
	<h1><?php echo self::html($locale); ?></h1>
	<p><?php if ($missing) echo _('Displaying messages that require translation.'); else echo _('Displaying installed messages.'); ?></p>
	<?php echo $library->drawTable(['message' => _('Message'), 'translation' => _('Translation')], [], ['id' => 'messages']); ?>
</div>