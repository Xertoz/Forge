<div class="infobox mailer" onclick="window.location = '/admin/Mailer'">
	<img class="icon" src="/components/Mailer/img/dashboard.75x75.png" alt="<?php echo self::l('Email'); ?>" title="<?php echo self::l('Email'); ?>" />
	<h2><?php echo self::l('Email'); ?>:</h2>
	<p><?php echo \forge\components\Mailer::isConfigured() ? 'OK' : '<span style="color:red;">! ! !</span>'; ?></p>
</div>