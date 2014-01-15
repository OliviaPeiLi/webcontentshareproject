<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';
echo "Starting on: ".ENVIRONMENT."\r\n";
while (1) {
	$res = mysql_query("SELECT * FROM users WHERE flag = 0 LIMIT 100");
	$has_more = false;
	while($row = mysql_fetch_object($res)) {
		$has_more = true;
		
		
	}
	if (!$has_more) {
		echo "DONE\n"; break;
	}
}