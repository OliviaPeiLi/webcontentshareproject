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

//Defaults (commented out) are = 1
$category_value = array(
	//'0'=>'1', '1'=>'1', '2'=>'1', '3'=>'1', '4'=>'1', '5'=>'1', '6'=>'1', '7'=>'1', '8'=>'1', '9'=>'1',
	//'10'=>'1','11'=>'1', '12'=>'1', '13'=>'1', '14'=>'1', '15'=>'1', '16'=>'1', '17'=>'1', '18'=>'1', '19'=>'1',
	//'20'=>'1', '21'=>'1', '22'=>'1', '23'=>'1', '24'=>'1', '25'=>'1', '26'=>'1', 
	//'388'=>'1', '389'=>'1',	'390'=>'1',	'391'=>'1',	
	//'392'=>'1.000001',
	//'393'=>'1',	'394'=>'1',
	//'395'=>'1.000001', '396'=>'1.000001',
	//'397'=>'1',	'398'=>'1',
	//'399'=>'1.000001', '400'=>'1.000001',
	//'401'=>'1',
	//'402'=>'1.000001', '403'=>'1.000001', '404'=>'1.000001', '405'=>'1.000001', '406'=>'1.000001', 
	//'407'=>'1',
	//'408'=>'1.000001', '409'=>'1.000001',
	//'410'=>'1', '411'=>'1',	'412'=>'1',	'413'=>'1',
	//'414'=>'1.3',
	//'415'=>'1', '416'=>'1',
);

$type_value = array(
	  //'embed'	  =>'1.000001',
	  //'html' => '1.000001',
	  //'content' => '1.000001',
	  //'screen' => '1.000001',
	  //'text' => '1.000001'
);

$gravity = 1.8;

$limit = 200; //How many news to calculate
$interval = 5; //each $interval seconds

/************************************************************************
*Reddit ranking algorithm
*http://amix.dk/blog/post/19588
************************************************************************/
function score($up_count, $comment_count, $redrop_count){
	return 1+$up_count+$comment_count+$redrop_count;
}

function hot($up_count, $comment_count, $redrop_count, $time){
	$s = score($up_count, $comment_count, $redrop_count);
	$s = $s==0 ? 1 : $s;
	$order = log(max(abs($s), 1), 10);
	$sign = $s>0 ? 1 : 0;
	//date_default_timezone_set('UTC');
	$seconds = $time - 1134028003;
	return intval(1000*round($order + $sign * $seconds / 45000, 7));
}
/***********************************************************************/

while (1) {
	echo "Recalculate $limit news at ".date('H:i:s Y-m-d')." ... ";
	$res = mysql_pquery("SELECT newsfeed_id, up_count, comment_count,collect_count, topic_id, link_type, type, unix_timestamp(time) as time 
							FROM newsfeed 
							LEFT JOIN topic_folders ON newsfeed.folder_id=topic_folders.folder_id
							WHERE is_ranked = 0 OR news_rank > 10000000
							ORDER BY newsfeed_id DESC
							LIMIT $limit
						");
	while ($row = mysql_fetch_object($res)) {
		/*OLD Algorithm from hacker news
		$h_age = floor((time() - $row->time) / 3600);
		
		$value = intval(
				1000*
				( isset($type_value[$row->link_type]) ? $type_value[$row->link_type] : 1)   
				* ( isset($category_value[$row->topic_id]) ? $category_value[$row->topic_id] : 1) 
				* round( ($row->up_count + 1) / pow($h_age + 2, $gravity), 4));
				//* round( 10 / pow($h_age + 2, $gravity), 4);
				//* round( 10 / pow(0 + 2, $gravity), 4);
				//*$row->time);
		*/
		$value = hot($row->up_count, $row->comment_count, $row->collect_count, $row->time);
		mysql_pquery("UPDATE newsfeed SET news_rank = {$value}, is_ranked = 1, ranked_at = NOW() WHERE newsfeed_id = {$row->newsfeed_id}");
	}
	echo "Calculated \r\n";
	sleep($interval);
}