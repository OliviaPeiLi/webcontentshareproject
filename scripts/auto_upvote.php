<?php
include_once dirname(__FILE__).'/config.php';
include_once dirname(__FILE__).'/db.php';
echo "\n";

$users = array();
$res = mysql_query("SELECT id FROM users WHERE role = 3");
while($row = mysql_fetch_object($res)) $users[] = $row->id;

$share_count = get_instance()->newsfeed_model->share_count;
$res = mysql_pquery("SELECT newsfeed_id, up_target, up_count, hits_target, hits, share_target, ($share_count) as share_count  FROM newsfeed 
					 WHERE 
					 	time < DATE_SUB(NOW(), INTERVAL 60 MINUTE)
					  	AND (hits_target > hits OR share_target > ($share_count)) 
					 ORDER BY ranked_at DESC LIMIT 300");
if (!mysql_num_rows($res)) {
	echo "Secondary \n";
	$res = mysql_pquery("SELECT newsfeed_id, up_target, up_count, hits_target, hits, share_target, ($share_count) as share_count  FROM newsfeed 
					 WHERE 
					 	time < DATE_SUB(NOW(), INTERVAL 60 MINUTE)
					  	AND (up_target > up_count OR hits_target > hits OR share_target > ($share_count)) 
					 ORDER BY ranked_at DESC LIMIT 300");
}

while($row = mysql_fetch_object($res)){
	echo "Newsfeed: ".$row->newsfeed_id." ";
	mysql_query("UPDATE newsfeed SET ranked_at = '0000-00-00 00:00:00' WHERE newsfeed_id = ".$row->newsfeed_id);
	$updated = false;
	if ($row->up_target > $row->up_count) {
		echo "liked ";
		get_instance()->like_model->insert(array('user_id'=>$users[array_rand($users)], 'newsfeed_id' => $row->newsfeed_id), true);
	}
	if ($row->hits_target > $row->hits) {
		$left = round( ($row->hits_target - $row->hits)/30 );
		$num = $left > 0 ? rand(1, $left) : 1;
		for ($i=0; $i<=$num;$i++) {
			echo "viewed ";
			mysql_query("UPDATE newsfeed SET hits = hits + 1 WHERE newsfeed_id = ".$row->newsfeed_id);
		}
	}
	if ($row->share_target > $row->share_count) {
		$left = round( ($row->share_target - $row->share_count)/30 );
		$num = $left > 0 ? rand(1, $left) : 1;
		for ($i=0; $i<=$num;$i++) {
			echo "shared ";
			get_instance()->newsfeed_share_model->insert(array('user_id'=>$users[array_rand($users)], 'newsfeed_id' => $row->newsfeed_id, 'api'=>'fb'), true);
		}
	}
	echo "\n";
}
	