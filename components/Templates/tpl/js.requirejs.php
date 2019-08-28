requirejs.config({baseUrl:'/',paths:{<?php
	$last = end($plugins);
	foreach ($plugins as $plugin => $data)
		echo "'$plugin':'".(is_array($data) ? $data[0] : $data)."'".($data !== $last ? "," : '');
?>},shim:{<?php
foreach ($plugins as $plugin => $data)
	if (is_array($data) && count($data[1])) {
		echo "'$plugin':{deps:[";

		$last2 = end($data[1]);
		foreach ($data[1] as $data2)
			echo "'$data2'".($data2 !== $last2 ? "," : '');

		echo "]}".($data !== $last ? "," : '');
	}
?>}});