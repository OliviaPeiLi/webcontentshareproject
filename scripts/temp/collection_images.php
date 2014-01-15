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
	
	//MARK latest 5 links or photos in each collection
	$res = mysql_query("SELECT folder_id FROM folder WHERE flag = 0 ORDER BY hits DESC LIMIT 1000");
	
	var_dump($res);
	while ($folder = mysql_fetch_assoc($res)) {
		if(!$folder) break;
		$newsfeed_res = mysql_query("SELECT newsfeed_id, activity_id, type FROM newsfeed WHERE folder_id = ".$folder['folder_id']." ORDER BY newsfeed_id DESC LIMIT 5");
		while($newsfeed = mysql_fetch_assoc($newsfeed_res)){
			if(!$newsfeed) break;
			if($newsfeed['type'] == 'link'){
				mysql_query("UPDATE links SET thumb_generated = '2' WHERE link_id = ".$newsfeed['activity_id']);
			}
			if($newsfeed['type'] == 'photo'){
				mysql_query("UPDATE photos SET thumb_generated = '2' WHERE photo_id = ".$newsfeed['activity_id']);
			} 
		}
		
		mysql_query("UPDATE folder SET flag = 1 WHERE folder_id = ".$folder['folder_id']."");
	}
	die('1000 done');
	
	
}

