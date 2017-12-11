<h1><?php echo self::l('Email'); ?></h1>
<?php echo self::response(['Mailer\Settings', 'Mailer\Server']); ?>
<div class="col-2">
	<div class="panel">
		<h1><?php echo self::l('Sender'); ?></h1>
		<form action="/admin/Mailer" method="post">
			<input type="hidden" name="forge[controller]" value="Mailer\Settings" />
			<p>
				<?php echo self::l('Name'); ?>:
				<?php echo self::input('text', 'name', $from['name']); ?>
			</p>
			<p>
				<?php echo self::l('Email'); ?>:
				<?php echo self::input('text', 'address', $from['address']); ?>
			</p>
			<p><input type="submit" value="<?php echo self::l('Save'); ?>"></p>
		</form>
	</div>
	<div class="panel red">
		<h1><?php echo self::l('Server'); ?></h1>
		<form action="/admin/Mailer" method="post">
			<input type="hidden" name="forge[controller]" value="Mailer\Server" />
			<p>
				<?php echo self::l('SMTP'); ?>:
				<?php echo self::input('checkbox', 'smtp', $smtp['use'], true, ['id' => 'smtp']); ?>
				<label for="smtp"><?php echo self::l('Use an external SMTP server'); ?></label>
			</p>
			<p>
				<?php echo self::l('Hostname'); ?>:
				<?php echo self::input('text', 'hostname', $smtp['hostname']); ?>
			</p>
			<p>
				<?php echo self::l('Username'); ?>:
				<?php echo self::input('text', 'username', $smtp['username']); ?>
			</p>
			<p>
				<?php echo self::l('Password'); ?>
				<?php echo self::input('password', 'password', $smtp['password']); ?>
			</p>
			<p><input type="submit" value="<?php echo self::l('Save'); ?>"></p>
		</form>
	</div>
</div>
<script type="text/javascript">
	function form_settings_success() {
		forge.displayMessage('<?php echo self::l('The settings were successfully saved.'); ?>',forge.MESSAGE_GOOD);
	}
	
	function form_settings_error() {
		forge.displayMessage('<?php echo self::l('The settings could not be saved.'); ?>',forge.MESSAGE_BAD);
	}
</script>