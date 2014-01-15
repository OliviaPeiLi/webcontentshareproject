<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';
echo "Starting on: ".ENVIRONMENT."\r\n";
$num = 0;
while (1) {
	$newsfeeds = mysql_query("SELECT newsfeed_id FROM  `newsfeed` WHERE NOT EXISTS (SELECT 1 FROM users WHERE user_id_from = users.id) LIMIT 100");
	$has_more = false;
	while($row = mysql_fetch_object($newsfeeds)) {
		echo "deleting: ".$row->newsfeed_id."\n";
		$num++;
		$has_more = true;
		if (! get_instance()->newsfeed_model->delete($row->newsfeed_id)) {
			echo "Could not delete record \n";
		}
	}
	if (!$has_more) {
		echo "DONE: $num\n"; break;
	}
}