<?php
	require_once '../forge.php';
	
	if ($key = \forge\Post::getString('devkey'))
		\forge\Memory::cookie('developer', $key);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Forge Installer</title>
		<link href="/css/tools.css" rel="stylesheet" media="screen" />
	</head>
	<body>
		<h1>Developer</h1>
		<p>You can use this tool to set a cookie on your machine. If it matches the website's developer key you will be given all debug info when an error occurs.</p>
		<form action="developer.php" method="POST">
			<p>
				Developer key:<br />
				<?=\forge\components\Templates\Engine::input('text', 'devkey', \forge\Memory::cookie('developer'))?>
			</p>
			<p>
				<input type="submit" value="Set key" />
			</p>
		</form>
	</body>
</html>