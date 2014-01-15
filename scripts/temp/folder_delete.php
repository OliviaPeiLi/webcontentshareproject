<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';


$folder_id = 2292524;
while(1) {
	$newsfeeds = mysql_query("SELECT newsfeed_id FROM newsfeed WHERE folder_id = $folder_id LIMIT 1000");
	$has_it = false;
	while ($row = mysql_fetch_object($newsfeeds)) {
		echo "Delete: ".$row->newsfeed_id."\n";
		$has_it = true;
		get_instance()->newsfeed_model->delete($row->newsfeed_id);
	}
	if (!$has_it) {
		echo "Done\n"; break;
	}	
}
