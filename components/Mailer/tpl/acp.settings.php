<div class="admin mailer">
	<h1><?php echo _('Email system'); ?></h1>
	<p><?php echo _('Every e-mail sent from this website will use the following settings. The system\'s email address and name that will appear in the \'From\' field must be set up. It is also possible to set up an external SMTP connection if neccessary.'); ?></p>
	<form action="/xml/Mailer/settings" method="post" name="settings">
		<table>
			<tr>
				<td colspan="2"><b><?php echo _('From'); ?></b></td>
				<td colspan="2"><b><?php echo _('Server'); ?></b></td>
			</tr>
			<tr>
				<td><?php echo _('Name'); ?>:</td>
				<td><input type="text" name="mailSettings[address][name]" value="<?php echo self::html($address['name']); ?>"></td>
				<td><?php echo _('SMTP'); ?>:</td>
				<td><input type="checkbox" name="mailSettings[server][smtp]" value="1"<?php if ($server['smtp']) echo ' checked'; ?>></td>
			</tr>
			<tr>
				<td><?php echo _('Email'); ?>:</td>
				<td><input type="text" name="mailSettings[address][from]" value="<?php echo self::html($address['from']); ?>"></td>
				<td><?php echo _('Hostname'); ?>:</td>
				<td><input type="text" name="mailSettings[server][hostname]" value="<?php echo self::html($server['hostname']); ?>"></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td><?php echo _('Username'); ?>:</td>
				<td><input type="text" name="mailSettings[server][username]" value="<?php echo self::html($server['username']); ?>"></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td><?php echo _('Password'); ?></td>
				<td><input type="password" name="mailSettings[server][password]"></td>
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