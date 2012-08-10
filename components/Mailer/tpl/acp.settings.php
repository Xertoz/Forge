<div class="admin mailer">
	<h1><?php echo _('Email system'); ?></h1>
	<p><?php echo _('Every e-mail sent from this website will use the following settings. The system\'s email address and name that will appear in the \'From\' field must be set up. It is also possible to set up an external SMTP connection if neccessary.'); ?></p>
	<form action="/admin/Mailer" method="post">
		<input type="hidden" name="forge[controller]" value="Mailer\Settings" />
		<?php echo self::response('Mailer\Settings'); ?>
		<table>
			<tr>
				<td colspan="2"><b><?php echo _('From'); ?></b></td>
				<td colspan="2"><b><?php echo _('Server'); ?></b></td>
			</tr>
			<tr>
				<td><?php echo _('Name'); ?>:</td>
				<td><?php echo self::input('text', 'from[name]', $from['name']); ?></td>
				<td><?php echo _('SMTP'); ?>:</td>
				<td><?php echo self::input('checkbox', 'smtp[use]', $smtp['use']); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Email'); ?>:</td>
				<td><?php echo self::input('text', 'from[address]', $from['address']); ?></td>
				<td><?php echo _('Hostname'); ?>:</td>
				<td><?php echo self::input('text', 'smtp[hostname]', $smtp['hostname']); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td><?php echo _('Username'); ?>:</td>
				<td><?php echo self::input('text', 'smtp[username]', $smtp['username']); ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td><?php echo _('Password'); ?></td>
				<td><?php echo self::input('password', 'smtp[password]', $smtp['password']); ?></td>
			</tr>
		</table>
		<p><input type="submit" value="<?php echo _('Save'); ?>"></p>
	</form>
</div>
<script type="text/javascript">
	function form_settings_success() {
		forge.displayMessage('<?php echo _('The settings were successfully saved.'); ?>',forge.MESSAGE_GOOD);
	}
	
	function form_settings_error() {
		forge.displayMessage('<?php echo _('The settings could not be saved.'); ?>',forge.MESSAGE_BAD);
	}
</script>