<div class="admin accounts account">
	<form action="/admin/Accounts/account<?php if (isset($_GET['id'])) echo '?id='.(int)$_GET['id']; ?>" method="post" name="account">
		<input type="hidden" name="forge[controller]" value="Accounts\Account" />
		<h1><?php echo _($account->getID()?'Edit Account':'Create Account'); ?></h1>
		<?php echo self::response('Accounts\Account'); ?>
		<input type="hidden" name="account[id]" value="<?php echo $account->getId(); ?>">
		<h2><?php echo _('Personal'); ?></h2>
		<table>
			<tr>
				<td><?php echo _('Account'); ?>:</td>
				<td><?php echo self::input('text', 'account[account]', $account->user_account); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Email'); ?>:</td>
				<td><?php echo self::input('text', 'account[email]', $account->user_email); ?></td>
			</tr>
			<tr>
				<td><?php echo _('First name'); ?>:</td>
				<td><?php echo self::input('text', 'account[name_first]', $account->user_name_first); ?></td>
			</tr>
			<tr>
				<td><?php echo _('Last name'); ?>:</td>
				<td><?php echo self::input('text', 'account[name_last]', $account->user_name_last); ?></td>
			</tr>
		</table>
		<h2><?php echo _('Permissions'); ?></h2>
		<table class="tablesorter">
			<thead>
				<tr>
					<th><?php echo _('Domain'); ?></th>
					<th><?php echo _('Category'); ?></th>
					<th><?php echo _('Field'); ?></th>
					<th><?php echo _('Read'); ?></th>
					<th><?php echo _('Write'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($domains as $domain => $domain_list): ?>
					<?php foreach ($domain_list as $category => $category_list): ?>
						<?php foreach ($category_list as $field): ?>
							<?php $read = isset($permissions[$domain][$category][$field]['read']) ? $permissions[$domain][$category][$field]['read'] : 0; ?>
							<?php $write = isset($permissions[$domain][$category][$field]['write']) ? $permissions[$domain][$category][$field]['write'] : 0; ?>
							<tr>
								<td><?php echo $domain; ?></td>
								<td><?php echo $category; ?></td>
								<td><?php echo $field; ?></td>
								<td class="permissive">
									<input type="hidden" name="permissions[<?php echo $domain; ?>][<?php echo $category; ?>][<?php echo $field; ?>][read]" value="<?php echo $read; ?>" />
									<img src="/images/led/accept.png" alt="<?php echo _('Yes'); ?>" onclick="revoke(this);"<?php if (!$read): ?> style="display:none;"<?php endif; ?> />
									<img src="/images/led/cross.png" alt="<?php echo _('No'); ?>" onclick="grant(this);"<?php if ($read): ?> style="display:none;"<?php endif; ?> />
								</td>
								<td class="permissive">
									<input type="hidden" name="permissions[<?php echo $domain; ?>][<?php echo $category; ?>][<?php echo $field; ?>][write]" value="<?php echo $write; ?>" />
									<img src="/images/led/accept.png" alt="<?php echo _('Yes'); ?>" onclick="revoke(this);"<?php if (!$write): ?> style="display:none;"<?php endif; ?> />
									<img src="/images/led/cross.png" alt="<?php echo _('No'); ?>" onclick="grant(this);"<?php if ($write): ?> style="display:none;"<?php endif; ?> />
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p><input type="submit" value="<?php echo _($account->getID()?'Save':'Create'); ?>" /></p>
	</form>
</div>