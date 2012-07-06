<div class="accounts login">
    <h1><?php echo _('Log in'); ?></h1>
    <?php if (isset($_GET['from'])): ?>
    	<p><?php echo _('The page you have requested requires that you authenticate. You can log in using this form:'); ?></p>
    <?php endif; ?>
    <form action="/user/login<?php if (isset($_GET['from'])): ?>?from=<?php echo urlencode($_GET['from']); endif; ?>" method="post">
    	<input type="hidden" name="forge[controller]" value="Accounts\Login" />
        <p><?php echo _('Account'); ?>:<br>
        <input name="account" type="text" value="<?php echo self::html($account); ?>"></p>
        <p><?php echo _('Password'); ?>:<br>
        <input name="password" type="password"></p>
        <p>
            <input type="checkbox" name="cookie" id="login_cookie"<?php if ($cookie): ?> checked="checked"<?php endif; ?>>
            <label for="login_cookie"><?php echo _('Remember me'); ?></label>
        </p>
        <p><input type="submit" value="<?php echo _('Log in'); ?>" onclick="return Login();"> <a href="/user/register"><?php echo _('Register'); ?></a></p>
        <p><a href="/user/lost-password"><?php echo _('Lost your password?'); ?></a></p>
        <p id="error" style="padding:5px 15px 5px 15px;display:none;border:1px solid red;background:#ffaaaa;"></p>
    </form>
    <?php if (\forge\Controller::getController() == 'Accounts\\Login' && \forge\Controller::getCode() == \forge\Controller::RESULT_BAD): ?>
        <p class="error"><?php echo \forge\Controller::getMessage(); ?></p>
    <?php endif; ?>
</div>