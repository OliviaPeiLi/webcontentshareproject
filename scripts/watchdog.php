<?php
if (!isset($_SERVER['argv'][1])) {
	die('Syntax: php watchdog.php <string:environment>');
}
define('ENVIRONMENT', $_SERVER['argv'][1]=='production' ? 'production' : 'staging');

if(ENVIRONMENT == 'staging') {
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
} elseif(ENVIRONMENT == 'production') {
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
} else {
	define('BASEPATH', __DIR__.'/../system/');
}

require_once(BASEPATH.'../scripts/config.php');
require_once(BASEPATH.'../application/config/config.php');
require_once(BASEPATH.'../scripts/db.php');

function get_scripts() {
	$return = array();
	$result = mysql_pquery("SELECT * FROM `scripts` ORDER BY `id` DESC");
	if($result) {
		while($row = mysql_fetch_assoc($result)) $return[] = $row;
		mysql_free_result($result);
		return $return;
	} else {
		return false;
	}
}

function parse_processes() {
	if(strpos(__DIR__, '/home/fandrop/') !== false) {
	   	$location = 'current';
	} elseif(strpos(__DIR__, '/home/test.fandrop/') !== false) {
		$location = 'current';
	} else {
	   	$location = 'public_html';
	}

	$ret = array();
	$proc=proc_open("ps ux", array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes); 
	fwrite($pipes[0], ''); fclose($pipes[0]); 
	$stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
	$stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
	$rtn=proc_close($proc); 
	$processes = explode("\n", $stdout);
	$headers = array();
	foreach (explode(" ", $processes[0]) as $key=>$header) {
		$header = trim($header); if (!$header) continue;
		$headers[$header] = '(?P<'.str_replace('%', '', $header).'>'.($header=='COMMAND' ? '.' : '[^ ]').'*?)';
	}
	unset($processes[0]);
	foreach ($processes as $key=>$line) {
		preg_match('#^'.implode("\s\s*", $headers).'$#si', $line, $proc);
		if (isset($proc['PID']) && $proc['PID']) $ret[] = $proc;
	}
	return $ret;
}

function running_instances($command, $processes) {
	$ret = array();
	echo "\r\nChecking: ".'php '.$command."\r\n";
	foreach ($processes as $process) {
		if (strpos($process['COMMAND'], 'php '.$command) !== FALSE) {
			list(,$instanse) = explode($command.' ', $process['COMMAND'],2);
			$ret[$instanse] = true;
		}
	}
	return $ret;
}

$scripts   = get_scripts();
$processes = parse_processes();
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	$location = 'current';
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	$location = 'current';
}elseif(strpos(__DIR__, '/home/endway/') !== false){
	$location = '/home/endway/public_html';
}else{
   	$location = '/home/radil/public_html';
}
foreach ($scripts as $script) {
	$running = running_instances($location.'/scripts/'.$script['name'].' '.ENVIRONMENT, $processes);
	for ($i=0;$i<$script['num_instances']; $i++) {
		if (isset($running[$i])) continue;
		$log = str_replace(array('/','.'), '_', $script['name'])."_".$i;
		$cmd = "php ".$location."/scripts/{$script['name']} ".ENVIRONMENT." $i > ".$log." &";
		echo "\r\n Starting... $cmd\r\n ";
		$ret = exec($cmd);
		echo "started";
	}
	echo "\r\n";
}