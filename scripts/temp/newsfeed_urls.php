<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';

while (1) {
	$newsfeeds = mysql_query("SELECT newsfeed_id, description FROM newsfeed WHERE url = '' ORDER BY newsfeed_id DESC LIMIT 100");
	$has_more = false;
	while($row = mysql_fetch_object($newsfeeds)) {
		$has_more = true;
		$url = $url_base = Url_helper::url_title($row->description);
		$i = 0;
		while (mysql_fetch_object(mysql_query("SELECT 1 FROM newsfeed WHERE url = '$url'"))) {
			$url = $url_base.'-'.$i; $i++;
		}
		mysql_query("UPDATE newsfeed SET url = '$url' WHERE newsfeed_id = ".$row->newsfeed_id);
		echo "processed: ".$row->newsfeed_id."\n";
	}
	if (!$has_more) {
		echo "DONE\n"; break;
	}
}