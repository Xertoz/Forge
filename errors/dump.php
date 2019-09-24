<?php
	// TODO: Fix /admin/ links in this file
	header('Cache-Control: no-store');

	$stack = function($array,$title) {
?>
<div class="box box-info">
	<div class="box-header with-border"><h1 class="box-title"><?php echo htmlentities($title); ?></h1></div>
	<div class="box-body">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Parameter</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($array)): foreach ($array as $key => $value): ?>
					<tr>
						<td><?php echo htmlentities($key); ?></td>
						<td><?php echo htmlentities($value); ?></td>
					</tr>
				<?php endforeach; else: ?>
					<tr>
						<td colspan="100%"><i>Empty</i></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
<?php
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Forge <?php echo FORGE_VERSION; ?> - Debug Stack <?php echo date('Y-m-d H:i:s'); ?></title>
		<style type="text/css">
			tr:hover {
				color:#3366cc;
			}

			th, td {
				text-align:left;
				padding-right:1em;
			}
		</style>
		<link href="/vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="/vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css" rel="stylesheet">
	</head>
	<body class="login-page" style="padding:50px;">
		<div class="login-logo"><b>Forge</b> <?=FORGE_VERSION?></div>
		<div class="box box-danger">
			<div class="box-header with-border"><h1 class="box-title">Exception</h1></div>
			<div class="box-body">
				<p><b><?php echo get_class($e); ?></b> was thrown in <b><?php echo htmlentities($e->getFile()); ?></b> at line <b><?php echo htmlentities($e->getLine()); ?></b> with the message <b><?php echo htmlentities($e->getMessage()); ?></b> and error code <b><?php echo htmlentities($e->getCode()); ?></b></p>
			</div>
		</div>
		<div class="box box-warning">
			<div class="box-header with-border"><h1 class="box-title">Stack trace</h1></div>
			<div class="box-body">
				<table class="table table-condensed">
					<thead>
					<tr>
						<th>Function</th>
						<th>Arguments</th>
						<th>File</th>
						<th>Line</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($e->getTrace() as $trace): ?>
						<tr>
							<td>
								<?php if (!empty($trace['class'])): ?>
								<a href="/admin/Documentation/lookup?class=<?=urlencode($trace['class'])?>&method=<?=$trace['function']?>"><?=htmlentities($trace['class'])?>::<?=htmlentities($trace['function'])?></a>
								<?php else: ?>
								<a href="/admin/Documentation/lookup?&function=<?=$trace['function']?>"><?=htmlentities($trace['function'])?></a></td>
								<?php endif; ?>
							<td>
								<?php if (isset($trace['args'])): ?>
									<table class="table table-condensed">
										<?php foreach ($trace['args'] as $arg): ?>
											<tr>
												<td><?php echo gettype($arg); ?></td>
												<td><?php
													switch (gettype($arg)) {
														default:
															echo '<span title="'.htmlentities($arg).'">'.htmlentities(substr($arg, 0, 32)).'</span>';
															break;
														case 'object':
															echo '<a href="/admin/Documentation/lookup?class='.urlencode(get_class($arg)).'">'.get_class($arg).'</a>';
															break;
														case 'array':
														case 'null':
															break;
													}
												?></td>
											</tr>
										<?php endforeach; ?>
									</table>
								<?php endif; ?>
							</td>
							<td><?=htmlentities($trace['file'])?></td>
							<td><?php echo isset($trace['line']) ? htmlentities($trace['line']) : null; ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="box box-primary">
			<div class="box-header with-border"><h1 class="box-title">Source</h1></div>
			<div class="box-body">
				<pre><?=str_replace(["\n", '<br />'], '', highlight_string(file_get_contents($e->getFile()), true))?></pre>
			</div>
		</div>
		<?php $stack($_SERVER,'$_SERVER'); ?>
		<?php $stack($_SESSION,'$_SESSION'); ?>
		<?php $stack($_COOKIE,'$_COOKIE'); ?>
		<?php $stack($_POST,'$_POST'); ?>
		<?php $stack($_GET,'$_GET'); ?>
		<?php $stack($_FILES,'$_FILES'); ?>
	</body>
</html>