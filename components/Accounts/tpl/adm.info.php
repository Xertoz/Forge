<div class="panel">
	<h1><?php echo self::l('Forge account'); ?></h1>
	<?php echo self::response('Accounts\Account'); ?>
	<form action="/admin/Identity/view?id=<?php echo (int)$_GET['id']; ?>" method="POST" id="form-info">
		<input type="hidden" name="forge[controller]" value="Accounts\Account" />
		<input type="hidden" name="account[id]" value="<?php echo (int)$_GET['id']; ?>" />
		<label for="form-info-name-account"><?php echo self::l('Account'); ?></label>
		<?php echo self::input('text', 'account[account]', $account->user_account, true, ['id' => 'form-info-name-account']); ?>
		<label for="form-info-name-first"><?php echo self::l('First name'); ?></label>
		<?php echo self::input('text', 'account[name_first]', $account->user_name_first, true, ['id' => 'form-info-name-first']); ?>
		<label for="form-info-name-last"><?php echo self::l('Last name'); ?></label>
		<?php echo self::input('text', 'account[name_last]', $account->user_name_last, true, ['id' => 'form-info-name-last']); ?>
		<label for="form-info-email"><?php echo self::l('E-mail address'); ?></label>
		<?php echo self::input('email', 'account[email]', $account->user_email, true, ['id' => 'form-info-email']); ?>
	</form>
	<footer>
		<button type="submit" form="form-info">Save</button>
	</footer>
</div>