<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';
echo "Starting on: ".ENVIRONMENT."\r\n";

$model = get_instance()->user_model;
unset($model->behaviors['uploadable']['avatar']['thumbnails']['small']); 
unset($model->behaviors['uploadable']['avatar']['thumbnails']['preview']); 
unset($model->behaviors['uploadable']['avatar']['thumbnails']['badge']); 
unset($model->behaviors['uploadable']['avatar']['thumbnails']['thumb']); 

while (1) {
	$newsfeeds = mysql_query("
						SELECT id, avatar FROM `users` 
						WHERE avatar <> '' AND flag = 0  
						ORDER BY id DESC LIMIT 1");
	$has_more = false;
	while($row = mysql_fetch_object($newsfeeds)) {
		echo "updating: ".$row->id."\n";
		$has_more = true;
		$model->behaviors['uploadable']['avatar']['filename'] = $row->avatar;
		$model->update($row->id, array('flag'=>1,'avatar'=>Url_helper::s3_url().'/users/'.$row->avatar));
	}
	if (!$has_more) {
		echo "DONE: \n"; break;
	}
}