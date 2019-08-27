<h1><?php echo self::l('Hi!'); ?></h1>
<p><?php echo self::l('We are sending this email to you because someone have requested a new password for this account.'); ?></p>
<p><?php echo sprintf(self::l('To reset the password for account %s, use the following link:'), $account->user_account); ?></p>
<p><a href="<?php echo $url = 'http://'.$_SERVER['HTTP_HOST'].'/user/recover-password?key='.$lost->key; ?>"><?php echo $url; ?></a></p>
<p><?php echo self::l('You have 24 hours to use the link, or it will be dismissed and you have to request a new one.'); ?></p>
<p><i><?php echo self::l('This was an automated message - you can\'t reply to it.'); ?></i></p>