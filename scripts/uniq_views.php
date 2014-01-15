<?php
if (!defined('ENVIRONMENT')) {
	if (strpos(__DIR__, '/fandrop/') !== false) {
		define('ENVIRONMENT', 'production');
		$_SERVER['HTTP_HOST'] = 'fandrop.com';
	} elseif (strpos(__DIR__, '/test.fandrop/') !== false) {
		define('ENVIRONMENT', 'staging');
		$_SERVER['HTTP_HOST'] = 'test.fandrop.com';
	} else {
		define('ENVIRONMENT', 'development');
		$_SERVER['HTTP_HOST'] = 'localhost';
	}
}
if (!defined('BASEPATH')) {
	if(ENVIRONMENT == 'staging') {
		define('BASEPATH', '/home/test.fandrop/current/system/');
	} elseif(ENVIRONMENT == 'production') {
		define('BASEPATH', '/home/fandrop/current/system/');
	} else {
		define('BASEPATH', __DIR__.'/../system/');
	}
}
if (!defined('APPPATH')) define('APPPATH', str_replace('system/', '', BASEPATH).'application/');
include_once dirname(__FILE__).'/db.php';
echo "Starting on: ".ENVIRONMENT."\r\n";

include_once APPPATH.'modules/fantoon-extensions/libraries/google/Analytics.php';

$driver = new Analytics();

$res = mysql_query("SELECT id, name, newsfeed.newsfeed_id, newsfeed.url FROM newsfeed_referrals JOIN newsfeed ON (newsfeed.newsfeed_id = newsfeed_referrals.newsfeed_id) 
					WHERE newsfeed_referrals.updated_at < DATE_SUB(NOW(), INTERVAL 60 MINUTE)
					LIMIT 300");

$has_res = false;
while($row = mysql_fetch_object($res)) {
	$has_res = true;
	$url = "/drop/".$row->url.'/'.$row->name;
	echo "Newsfeed: $url -> ";
	$num = $driver->get_page($url);
	echo $num." \n";
	mysql_query("UPDATE newsfeed_referrals SET updated_at = NOW(), views = ".$num." WHERE id = ".$row->id);
	sleep(1);
}

if ($has_res) {
	$contest_id = mysql_fetch_object(mysql_query("SELECT id FROM contests WHERE url = 'cite'"))->id;
	$res = mysql_query("SELECT newsfeed_id, url FROM newsfeed WHERE folder_id IN (
							SELECT folder_id FROM folder WHERE contest_id = $contest_id
						) LIMIT 300");
	
	$has_res = false;
	while($row = mysql_fetch_object($res)) {
		$has_res = true;
		$url = "/drop/".$row->url;
		echo "Newsfeed: $url -> ";
		$num = $driver->get_page($url);
		echo $num." \n";
		mysql_query("UPDATE newsfeed SET uniqview = ".$num." WHERE newsfeed_id = ".$row->newsfeed_id);
		sleep(1);
	}
}

if (!$has_res) sleep(60);