<div class="admin accounts list">
    <h1><?php echo _('Accounts'); ?></h1>
    <?php echo $accounts->drawTable(
        array(
            'user_account' => _('Account'),
            'name' => _('Name'),
            'user_email' => _('Email'),
            'actions' => null
        ),
        array(
            'name' => function($r) {
                return $r['user_name_first'].' '.$r['user_name_last'];
            },
            'actions' => function($r) {
                $output = '<a href="/admin/Accounts/account?id='.$r['forge_id'].'"><img src="/images/led/application_edit.png" alt="'._('Settings').'" title="'._('Settings').'" /></a>'.PHP_EOL;
				$output .= '<form action="/admin/Accounts" method="POST">';
				$output .= '<input type="hidden" name="forge[controller]" value="Accounts\Account" />';
				$output .= '<input type="hidden" name="delete" value="1" />';
				$output .= self::input('hidden', 'account[id]', $r['forge_id'], false);
				$output .= '<input type="image" src="/images/led/cross.png" onclick="return confirm(\''.sprintf(_('Do you want to delete the account %s?\n\nThis action is irreversible.'), self::html($r['user_account'])).'\');" />';
				$output .= '</form>';
                return $output;
            }
        )
    ); ?>
</div>