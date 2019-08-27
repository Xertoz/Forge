<h1><?php echo self::l('Email'); ?></h1>
<?php echo self::response(['Mailer\Settings', 'Mailer\Server']); ?>
<div class="col-2">
	<div class="panel">
		<h1><?php echo self::l('Sender'); ?></h1>
		<form action="/admin/Mailer" method="post" id="mailer-settings">
			<input type="hidden" name="forge[controller]" value="Mailer\Settings" />
			<p>
				<?php echo self::l('Name'); ?>:
				<?php echo self::input('text', 'name', $from['name']); ?>
			</p>
			<p>
				<?php echo self::l('Email'); ?>:
				<?php echo self::input('text', 'address', $from['address']); ?>
			</p>
		</form>
        <footer>
            <button type="submit" form="mailer-settings"><?php echo self::l('Save'); ?></button>
        </footer>
	</div>
</div>
<div class="col-2">
	<div class="panel red">
		<h1><?php echo self::l('Server'); ?></h1>
		<form action="/admin/Mailer" method="post" id="mailer-server">
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
		</form>
        <footer>
            <button type="submit" form="mailer-server"><?php echo self::l('Save'); ?></button>
        </footer>
	</div>
</div>