<?php
/**
 * This script parses all js files we have and sends the output to the wiki
 */

$log = TRUE;
$js_modules_path = 'application/modules';
require_once 'js_file_parser.php'; //to parse js files
require_once 'wiki.php'; //to generate the wiki page and send the output

/**
 * Send a message to the output
 * @param (String) $msg
 * @param (Int) $indent
 */
function _log($msg, $indent = 0) {
	global $log;
	if ($log) {
		if (is_string($msg)) {
			for ($i=0;$i<$indent;$i++) $msg = '    '.$msg;
			echo $msg."\r\n";
		} else {
			print_r($msg);
		}
	}
}

/**
 * Lists all used js files
 */
function get_js_modules() {
	global $js_modules_path;
	$files = array();
	//_log('=== Listing files ===');
	foreach (glob($js_modules_path.'/*/js/*', GLOB_ONLYDIR) as $module) {
		if (strpos($module, '~') !== false) continue;
		if (strpos($module, '/tests') !== false) continue;
		if (strpos($module, '/plugins') !== false) continue;
		//_log($module);
		foreach (glob($module.'/*.js') as $js_file) {
			if (strpos($js_file, '~') !== false) continue;
			//Bookmarklet files need manual documentation
			if (strpos($js_file, '/bookmarklet/external') !== false) continue;
			if (strpos($js_file, '/bookmarklet/clipboard') !== false) continue;
			if (strpos($js_file, '/bookmarklet/mentions') !== false) continue;
			//_log(basename($js_file), 1);
			$files[basename($module)][basename($js_file)] = array(
				'location' => $js_file
			);
		}
	}
	//_log($files);
	return $files;
}

/**
 * RUN
 */
$files = get_js_modules();
foreach ($files as $module=>&$contents) {
	foreach ($contents as $js_file=>&$data) {
		$parser = new Js_file_parser($data['location']);
		$data = $parser->parse();
		//die(print_r($data));
	}
}

$wiki = new Wiki($files);
$wiki->render()->send();
