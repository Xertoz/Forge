<?php require_once '../forge.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Forge Installer</title>
		<link href="/css/tools.css" rel="stylesheet" media="screen" />
	</head>
	<body>
		<h1>Installation</h1>
		<p>Welcome to this installation guide which will guide you through a few required steps in order to install Forge on your system.</p>
		<p>Please make sure that you have recieved the web, database and mail server information from your host before proceeding!</p>
		<h2>Step 1 - Prerequisites</h2>
		<p>This set of minimum requirements must be met or Forge won\'t be able to function properly.</p>
		<?php
		function check_extension(\forge\Checklist $chk, $ext) {
			$chk->add(extension_loaded($ext), sprintf('PHP extension %s is loaded.', $ext));
		}
		$prerequisites = new forge\Checklist();
		$prerequisites->add(true, 'Forge is loadable.');
		$prerequisites->add(count(glob(FORGE_PATH.'/config/*')) === 0, 'Forge isn\'t already installed.');
		$prerequisites->add(is_writable(FORGE_PATH.'/config'), 'The config folder is writable.');
		$prerequisites->add(is_writable(FORGE_PATH.'/files'), 'The files folder is writable.');
		$prerequisites->add(substr(phpversion(), 0, strlen('5.4')) >= 5.4, 'PHP version is at least 5.4.');
		check_extension($prerequisites, 'curl');
		check_extension($prerequisites, 'fileinfo');
		check_extension($prerequisites, 'gd');
		check_extension($prerequisites, 'hash');
		check_extension($prerequisites, 'intl');
		check_extension($prerequisites, 'pcre');
		check_extension($prerequisites, 'PDO');
		check_extension($prerequisites, 'PDO_mysql');
		check_extension($prerequisites, 'session');
		check_extension($prerequisites, 'xmlwriter');
		echo $prerequisites;
		?>
		<?php if (!$prerequisites->isChecked()): ?>
		<p class="error">You must take the proper steps to ensure that the prerequisites are met.</p>
		<?php goto footer; endif; ?>
		<form action="/tools/install.php" method="POST">
			<h2>Step 2 - Settings</h2>
			<h3>Database</h3>
			<p>Forge requires a main database to store data within. It is possible to add additional database connections later.</p>
			<p>The prefix field is freely selectable - just choose a table prefix that is unique to all software using the database!</p>
			<table>
				<tbody>
					<tr>
						<td>System:</td>
						<td>
							<select name="database[system]">
								<option value="MySQL">MySQL</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Hostname:</td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[hostname]'); ?></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[username]'); ?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'database[password]'); ?></td>
					</tr>
					<tr>
						<td>Database:</td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[database]'); ?></td>
					</tr>
					<tr>
						<td>Prefix:</td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'database[prefix]'); ?></td>
					</tr>
				</tbody>
			</table>
			<h3>Root</h3>
			<p>Every Forge system has a root user, which is granted all permissions without having an account.</p>
			<p>The root user shouldn\'t be given to any non-developers.</p>
			<table>
				<tbody>
					<tr>
						<td>Name:</td>
						<td><?php echo \forge\components\Templates\Engine::input('text', 'root[name]'); ?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'root[password1]'); ?></td>
					</tr>
					<tr>
						<td>Confirm:</td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'root[password2]'); ?></td>
					</tr>
				</tbody>
			</table>
			<h3>Development</h3>
			<p>Any client which wishes to read debug data upon fatal errors must provide the secret development key.</p>
			<table>
				<tbody>
					<tr>
						<td>Development key:</td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'development[key1]'); ?></td>
					</tr>
					<tr>
						<td>Confirm:</td>
						<td><?php echo \forge\components\Templates\Engine::input('password', 'development[key2]'); ?></td>
					</tr>
				</tbody>
			</table>
			<h2>Step 3 - Install</h2>
			<?php if (count($_POST) > 0): ?>
				<?php
				$install = new \forge\Checklist();
				$install->add(!empty($_POST['database']['system']), 'Database system must be selected.');
				$install->add(!empty($_POST['database']['hostname']), 'Database hostname must be given.');
				$install->add(!empty($_POST['database']['username']), 'Database username must be given.');
				$install->add(!empty($_POST['database']['password']), 'Database password must be given.');
				$install->add(!empty($_POST['database']['database']), 'Database name must be given.');
				$install->add(!empty($_POST['database']['prefix']), 'Database table prefix must be given.');
				$install->add(!empty($_POST['root']['name']), 'Root name must be given.');
				$install->add(!empty($_POST['root']['password1']), 'Root password must be given.');
				$install->add(!empty($_POST['root']['password2']) && strcmp($_POST['root']['password1'], $_POST['root']['password2']) == 0, 'Root password must be confirmed.');
				$install->add(!empty($_POST['development']['key1']), 'Development key must be given.');
				$install->add(!empty($_POST['development']['key1']) && strcmp($_POST['development']['key1'], $_POST['development']['key2']) == 0, 'Development key must be confirmed.');

				if (!$install->isChecked())
					goto results;

				// TODO: Install default pages that replace handlers

				// Write the developer key
				try {
					\forge\components\Identity::setDeveloperKey($_POST['development']['key1']);
					$install->add(true, 'Setting the development key.');
				}
				catch (\Exception $e) {
					$install->add(false, 'Setting the development key.');
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
					$install->add(true, 'Adding the database connection.');
					foreach (\forge\Addon::getComponents() as $component)
						try {
							\forge\components\Databases::fixDatabase($component,'COM');
							$install->add(true, sprintf('Installing component %s into the database', $component));
						}
						catch (\Exception $e) {
							$install->add(false, sprintf('Installing component %s into the database', $component));
						}
					foreach (\forge\Addon::getModules() as $module)
						try {
							\forge\components\Databases::fixDatabase($module,'MOD');
							$install->add(true, sprintf('Installing module %s into the database', $module));
						}
						catch (\Exception $e) {
							$install->add(false, sprintf('Installing module %s into the database', $module));
						}
				}
				catch (\Exception $e) {
					$install->add(false, 'Adding the database connection.');
				}

				// Set up the root account
				try {
					$domain = strstr($_SERVER['HTTP_HOST'], '.') ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'].'.com';
					$account = \forge\components\Accounts::createAccount(
						$_POST['root']['name'],
						'Super',
						'User',
						'noreply@'.$domain,
						$_POST['root']['password1'],
						$_POST['root']['password2'],
						false
					);
					$account->user_state = 'active';
					$account->save();
					$identity = new \forge\components\Accounts\identities\Account($account->getId());
					$install->add(true, 'Setting up the root user.');
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
					$install->add(false, 'Setting up the root user.');
				}

				// Set up the host
				try {
					$website = new \forge\components\Websites\db\Website();
					$website->domain = $_SERVER['HTTP_HOST'];
					$website->insert();
					$install->add(true, sprintf('Installing website %s', $_SERVER['HTTP_HOST']));
				}
				catch (\Exception $e) {
					$install->add(false, sprintf('Installing website %s'.$e->getMessage(), $_SERVER['HTTP_HOST']));
				}

				// Set the default template
				try {
					\forge\components\Templates::setTemplate('anvil', true, $_SERVER['HTTP_HOST']);
					$install->add(true, 'Installing default template');
				}
				catch (\Exception $e) {
					$install->add(false, 'Installing default template');
				}

				// Create the neccessary file repositories
				try {
					\forge\components\Files::createRepositories();
					$install->add(true, 'Creating file repositories');
				} catch (\Exception $e) {
					$install->add(false, 'Creating file repositories');
				}

				// Remove any configured files on failure
				if (!$install->isChecked())
					if (($files = glob(FORGE_PATH.'/config/*')) !== false)
						foreach ($files as $file)
							(new \forge\components\Files\ConfigFile(substr($file, strlen(FORGE_PATH.'/config/'))))->delete();

				results:
				echo $install;
				if (!$install->isChecked())
					echo '<p class="error">'.'Installation failed!'.'</p>';
				else {
					echo '<p class="success">'.'Forge was installed!'.'</p>';
					goto clean;
				}
				?>
			<?php endif; ?>
			<p>You may proceed with the installation once you have set up the fields in step 2 accordingly to your server environment.</p>
			<p><input type="submit" value="Install" /></p>
			<?php clean: ?>
		</form>
		<?php footer: ?>
	</body>
</html>
