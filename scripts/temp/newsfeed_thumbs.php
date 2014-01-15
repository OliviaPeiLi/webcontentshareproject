<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';
echo "Starting on: ".ENVIRONMENT."\r\n";

ini_set('display_startup_errors', 'On');
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('MP_DB_DEBUG', true); 

$model = get_instance()->newsfeed_model;
unset($model->behaviors['uploadable']['img']['thumbnails']['full']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['thumb']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['tile']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['small']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['square']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['bigsquare']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['229']); 
unset($model->behaviors['uploadable']['img']['thumbnails']['576']); 
print_r(array_keys($model->behaviors['uploadable']['img']['thumbnails']));
while (1) {
	$newsfeeds = mysql_query("
						SELECT newsfeed_id, img FROM  `newsfeed` 
						WHERE img <> '' AND thumb_generated < 2  
						ORDER BY newsfeed_id DESC LIMIT 100");
	$has_more = false;
	while($row = mysql_fetch_object($newsfeeds)) {
		echo "updating: ".$row->newsfeed_id;
		$has_more = true;
		$model->behaviors['uploadable']['img']['filename'] = $row->img;
		$img = Url_helper::s3_url().'links/'.(str_replace('.', '_original.', $row->img));
		if (!@fopen($img, 'r')) $img = str_replace('_original.', '.', $img);
		echo "<";
		if (!@fopen($img, 'r')) $img = Url_helper::s3_url().'links/'.(str_replace('.', '_full.', $row->img));
		echo "<";
		if (!@fopen($img, 'r')) {
			$model->update($row->newsfeed_id, array('thumb_generated'=>2), true);
			continue;
		}
		$model->update($row->newsfeed_id, array('thumb_generated'=>2,'img'=>$img), true);
		echo " <- \n";
	}
	if (!$has_more) {
		echo "DONE: \n"; break;
	}
}