requirejs.config({
	paths: {
<?php
	$last = end($plugins);
	foreach ($plugins as $plugin => $url)
		echo "\t\t'$plugin': '$url'".($url !== $last ? ",\n" : '');
?>
	}
});