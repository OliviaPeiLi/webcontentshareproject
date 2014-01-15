<?php 
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
}else{
   	define('ENVIRONMENT', 'development');
}

if(ENVIRONMENT == 'staging'){
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}elseif(ENVIRONMENT == 'production'){
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}else{
	define('BASEPATH', __DIR__.'/../../system/');
}
include(BASEPATH.'../scripts/config.php');
include(BASEPATH.'../application/config/config.php');
include_once BASEPATH.'../scripts/db.php';


while (1) {

	$res = mysql_query("SELECT newsfeed_id, activity_id,type FROM newsfeed WHERE img_width=0 ORDER BY newsfeed_id DESC LIMIT 1000");
	
	while ($newsfeed = mysql_fetch_assoc($res)) {
		
		if(!$newsfeed) break;
		if($newsfeed['type'] == 'link'){
			$links = mysql_query("SELECT img_width, img_height FROM links WHERE link_id=".$newsfeed['activity_id']);
			$link = mysql_fetch_object($links);
			
		}
		if($newsfeed['type'] == 'photo'){
			$links = mysql_query("SELECT img_width, img_height FROM photos WHERE photo_id=".$newsfeed['activity_id']);
			$link = mysql_fetch_object($links);
			
			
		} 
		mysql_query("UPDATE newsfeed SET img_width = '".$link->img_width."', img_height = '".$link->img_height."' WHERE newsfeed_id = '".$newsfeed['newsfeed_id']."'");

	}
	//die('1000 done');
	echo 'one load is done';
	
	
}

