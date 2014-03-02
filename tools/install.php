<?php require_once '../forge.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo _('Forge Installer'); ?></title>
		<link href="/css/tools.css" rel="stylesheet" media="screen" />
	</head>
	<body>
		<h1><?php echo _('Installation'); ?></h1>
		<p><?php echo _('Welcome to this installation guide which will guide you through a few required steps in order to install Forge on your system.'); ?></p>
		<p><?php echo _('Please make sure that you have recieved the web, database and mail server information from your host before proceeding!'); ?></p>
		<h2><?php echo _('Step 1 - Prerequisites'); ?></h2>
		<p><?php echo _('This set of minimum requirements must be met or Forge won\'t be able to function properly.'); ?></p>
		<?php
		// TODO: Add checks for all required PHP modules
		$prerequisites = new forge\Checklist();
		$prerequisites->add(true, _('Forge is loadable.'));
		$prerequisites->add(count(glob(FORGE_PATH.'/config/*')) === 0, _('Forge isn\'t already installed.'));
		$prerequisites->add(is_writable(FORGE_PATH.'/config'), _('The config folder is writable.'));
		$prerequisites->add(is_writable(FORGE_PATH.'/files'), _('The files folder is writable.'));
		$prerequisites->add(substr(phpversion(), 0, strlen('5.4')) == '5.4', _('PHP version is in the 5.4 family.'));
		$prerequisites->add(extension_loaded('curl'), _('PHP extension curl is loaded.'));
		$prerequisites->add(extension_loaded('fileinfo'), _('PHP extension fileinfo is loaded.'));
		$prerequisites->add(extension_loaded('gettext'), _('PHP extension gettext is loaded.'));
		$prerequisites->add(extension_loaded('gd'), _('PHP extension gd is loaded.'));
		$prerequisites->add(extension_loaded('hash'), _('PHP extension hash is loaded.'));
		$prerequisites->add(extension_loaded('pcre'), _('PHP extension pcre is loaded.'));
		$prerequisites->add(extension_loaded('PDO'), _('PHP extension PDO is loaded.'));
		$prerequisites->add(extension_loaded('pdo_mysql'), _('PHP extension pdo_mysql is loaded.'));
		$prerequisites->add(extension_loaded('session'), _('PHP extension session is loaded.'));
		$prerequisites->add(extension_loaded('xmlwriter'), _('PHP extension xmlwriter is loaded.'));
		echo $prerequisites;
		?>
		<?php if (!$prerequisites->isChecked()): ?>
		<p class="error"><?php echo _('You must take the proper steps to ensure that the prerequisites are met.'); ?></p>
		<?php goto footer; endif; ?>
		<form action="/tools/install.php" method="POST">
			<h2><?php echo _('Step 2 - Settings'); ?></h2>
			<h3><?php echo _('Database'); ?></h3>
			<p><?php echo _('Forge requires a main database to store data within. It is possible to add additional database connections later.'); ?></p>
			<p><?php echo _('The prefix field is freely selectable - just choose a table prefix that is unique to all software using the database!'); ?></p>
			<table>
				<tbody>
					<tr>
						<td><?php echo _('System:'); ?></td>
						<td>
							<select name="database[system]">
								<option value="MySQL"><?php echo _('MySQL'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><?php echo _('Hostname:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[hostname]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Username:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[username]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Password:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'database[password]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Database:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[database]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Prefix:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[prefix]'); ?></td>
					</tr>
				</tbody>
			</table>
			<h3><?php echo _('Root'); ?></h3>
			<p><?php echo _('Every Forge system has a root user, which is granted all permissions without having an account.'); ?></p>
			<p><?php echo _('The root user shouldn\'t be given to any non-developers.'); ?></p>
			<table>
				<tbody>
					<tr>
						<td><?php echo _('Name:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'root[name]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Password:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'root[password1]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Confirm:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'root[password2]'); ?></td>
					</tr>
				</tbody>
			</table>
			<h3>Development</h3>
			<p><?php echo _('Any client which wishes to read debug data upon fatal errors must provide the secret development key.'); ?></p>
			<table>
				<tbody>
					<tr>
						<td><?php echo _('Development key:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'development[key1]'); ?></td>
					</tr>
					<tr>
						<td><?php echo _('Confirm:'); ?></td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'development[key2]'); ?></td>
					</tr>
				</tbody>
			</table>
			<h2><?php echo _('Step 3 - Install'); ?></h2>
			<?php if (count($_POST) > 0): ?>
				<?php
				$install = new \forge\Checklist();
				$install->add(!empty($_POST['database']['system']), _('Database system must be selected.'));
				$install->add(!empty($_POST['database']['hostname']), _('Database hostname must be given.'));
				$install->add(!empty($_POST['database']['username']), _('Database username must be given.'));
				$install->add(!empty($_POST['database']['password']), _('Database password must be given.'));
				$install->add(!empty($_POST['database']['database']), _('Database name must be given.'));
				$install->add(!empty($_POST['database']['prefix']), _('Database table prefix must be given.'));
				$install->add(!empty($_POST['root']['name']), _('Root name must be given.'));
				$install->add(!empty($_POST['root']['password1']), _('Root password must be given.'));
				$install->add(!empty($_POST['root']['password2']) && strcmp($_POST['root']['password1'], $_POST['root']['password2']) == 0, _('Root password must be confirmed.'));
				$install->add(!empty($_POST['development']['key1']), _('Development key must be given.'));
				$install->add(!empty($_POST['development']['key1']) && strcmp($_POST['development']['key1'], $_POST['development']['key2']) == 0, _('Development key must be confirmed.'));

				if (!$install->isChecked())
					goto results;

				// Install the handlers
				$handlers = [
					'user' => 'forge\components\Accounts\UserHandler',
					'admin' => 'forge\components\Admin\AdminHandler',
					//'files' => 'forge\components\Files\FileRequest',
					'thumbnail' => 'forge\components\Files\ThumbnailRequest',
					null => 'forge\components\SiteMap\PageHandler',
					'robots.txt' => 'forge\components\SiteMap\SiteMapHandler',
					'sitemap' => 'forge\components\SiteMap\SiteMapHandler',
					'xml' => 'forge\components\XML\XMLHandler',
					'json' => 'forge\components\JSON\JSONHandler',
					'identity' => 'forge\components\Identity\RequestHandler'
				];
				foreach ($handlers as $base => $handler)
					try {
						\forge\RequestHandler::register($base, $handler);
						$install->add(true, sprintf(_('Installing handler on %s'), '/'.$base));
					}
					catch (\Exception $e) {
						$install->add(false, sprintf(_('Installing handler on %s'), '/'.$base));
					}

				// Write the developer key
				try {
					\forge\components\Identity::setDeveloperKey($_POST['development']['key1']);
					$install->add(true, _('Setting the development key.'));
				}
				catch (\Exception $e) {
					$install->add(false, _('Setting the development key.'));
				}

				// Set up the database connection
				try {
					$databaseId = \forge\components\Databases::AddConnection(
						$_POST['database']['system'],
						$_POST['database']['hostname'],
						$_POST['database']['database'],
						$_POST['database']['prefix'],
						$_POST['database']['username'],
						$_POST['database']['password']
					);
					\forge\components\Databases::SetDefaultConnection($databaseId);
					$install->add(true, _('Adding the database connection.'));
					foreach (\forge\Addon::getComponents() as $component)
						try {
							\forge\components\Databases::fixDatabase($component,'COM');
							$install->add(true, sprintf(_('Installing component %s into the database'), $component));
						}
						catch (\Exception $e) {
							$install->add(false, sprintf(_('Installing component %s into the database'), $component));
						}
					foreach (\forge\Addon::getModules() as $module)
						try {
							\forge\components\Databases::fixDatabase($module,'MOD');
							$install->add(true, sprintf(_('Installing module %s into the database'), $module));
						}
						catch (\Exception $e) {
							$install->add(false, sprintf(_('Installing module %s into the database'), $module));
						}
				}
				catch (\Exception $e) {
					$install->add(false, _('Adding the database connection.'));
				}

				// Set up the root account
				try {
					$account = \forge\components\Accounts::createAccount(
						$_POST['root']['name'],
						'Super',
						'User',
						'noreply@'.$_SERVER['HTTP_HOST'],
						$_POST['root']['password1'],
						$_POST['root']['password2'],
						false
					);
					$account->user_state = 'active';
					$account->save();
					$identity = new \forge\components\Accounts\identities\Account($account->getId());
					$install->add(true, _('Setting up the root user.'));
					foreach (\forge\Addon::getAddons(true) as $addon) {
						$permissions = $addon::getPermissions();
						foreach ($permissions as $permission) {
							$entity = new \forge\components\Identity\db\Permission();
							$entity->identity = $identity->getId();
							$entity->permission = $permission;
							$entity->insert();
						}
					}
				}
				catch (\Exception $e) {
					$install->add(false, _('Setting up the root user.'));
				}

				// Set up the host
				try {
					$website = new \forge\components\Websites\db\Website();
					$website->domain = $_SERVER['HTTP_HOST'];
					$website->insert();
					$install->add(true, sprintf(_('Installing website %s'), $_SERVER['HTTP_HOST']));
				}
				catch (\Exception $e) {
					$install->add(false, sprintf(_('Installing website %s').$e->getMessage(), $_SERVER['HTTP_HOST']));
				}

				// Set the default template
				try {
					\forge\components\Templates::setTemplate('anvil', true, $_SERVER['HTTP_HOST']);
					$install->add(true, _('Installing default template'));
				}
				catch (\Exception $e) {
					$install->add(false, _('Installing default template'));
				}

				// Remove any configured files on failure
				if (!$install->isChecked())
					if (($files = glob(FORGE_PATH.'/config/*')) !== false)
						foreach ($files as $file)
							(new \forge\components\Files\ConfigFile(substr($file, strlen(FORGE_PATH.'/config/'))))->delete();

				results:
				echo $install;
				if (!$install->isChecked())
					echo '<p class="error">'._('Installation failed!').'</p>';
				else {
					echo '<p class="success">'._('Forge was installed!').'</p>';
					goto clean;
				}
				?>
			<?php endif; ?>
			<p><?php echo _('You may proceed with the installation once you have set up the fields in step 2 accordingly to your server environment.'); ?></p>
			<p><input type="submit" value="<?php echo _('Install'); ?>" /></p>
			<?php clean: ?>
		</form>
		<?php footer: ?>
	</body>
</html>