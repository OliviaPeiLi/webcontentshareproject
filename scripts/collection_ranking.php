<?php
define('BASEPATH', __DIR__.'/../system/');
if (!defined('ENVIRONMENT')) {
	if (strpos(__DIR__, '/fandrop/') !== false) {
		define('ENVIRONMENT', 'production');
	} elseif (strpos(__DIR__, '/test.fandrop/') !== false) {
		define('ENVIRONMENT', 'staging');
	} else {
		define('ENVIRONMENT', 'development');
	}
}
include(BASEPATH.'../scripts/db.php');

$limit = 200; //How many news to calculate
$interval = 5; //each $interval seconds


function hot($folder_id){
	$res = mysql_pquery("SELECT newsfeed_id, news_rank
							FROM newsfeed
							WHERE folder_id = $folder_id
							ORDER BY newsfeed_id DESC
							LIMIT 4
						");
	while($newsfeed = mysql_fetch_object($res)){
		$newsfeeds[] = $newsfeed;
	}

	if(@count($newsfeeds)<3) return 0;
	return intval(($newsfeeds[0]->news_rank + $newsfeeds[1]->news_rank + $newsfeeds[2]->news_rank)/3);
}
/***********************************************************************/

while (1) {
	echo "Recalculate $limit collections at ".date('H:i:s Y-m-d')." ... ";
	$res = mysql_pquery("SELECT folder_id
							FROM folder 
							WHERE is_ranked = 0
							ORDER BY folder_id DESC
							LIMIT $limit
						");
	while ($row = mysql_fetch_object($res)) {
		$value = hot($row->folder_id);
		mysql_pquery("UPDATE folder SET ranking = {$value}, is_ranked = 1, ranked_at = NOW() WHERE folder_id = {$row->folder_id}");
	}
	echo "Calculated \r\n";
	sleep($interval);
}