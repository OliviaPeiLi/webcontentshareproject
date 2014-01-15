<?php
$file = ltrim($_SERVER['REQUEST_URI'], '/');
if (strpos($file, '?') !== false) list($file,) = explode('?', $file);
if (is_file($file)) {
	if (strpos($file, '.css') !== false) header('Content-Type:text/css');
	elseif (strpos($file, '.js') !== false) header('Content-Type:application/javascript');
	echo file_get_contents($file);
	exit ;
} else {
	$file = str_replace('modules/', '', $file);
	foreach (glob('application/modules/*', GLOB_ONLYDIR) as $path) {
		if (is_file($path.'/'.$file)) {
			if (strpos($file, '.css') !== false) header('Content-Type:text/css');
			elseif (strpos($file, '.js') !== false) header('Content-Type:application/javascript');
			echo file_get_contents($path.'/'.$file);
			exit ;
		}
	}
}
die('File not found: '.$file);