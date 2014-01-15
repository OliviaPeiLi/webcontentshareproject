<?php
if(strpos(__DIR__, '/home/fandrop/') !== false) {
   	define('ENVIRONMENT', 'production');
	define('ROOTPATH', '/home/fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
	define('ENVIRONMENT', 'staging');
	define('ROOTPATH', '/home/test.fandrop');
	define('BASEPATH', ROOTPATH.'/current/system/');
}else{
   	define('ENVIRONMENT', 'development');
	define('BASEPATH', __DIR__.'/../../system/');
}
include_once BASEPATH.'../scripts/db.php';

while (1) {
	$folders = mysql_query("SELECT folder_id FROM folder WHERE flag = 0 LIMIT 100");
	$has_it = false;
	while ($folder = mysql_fetch_object($folders)) {
		$has_it = true;
		echo "Folder: ".$folder->folder_id."\r\n";
		$items = array();
		$newsfeeds = mysql_query("SELECT newsfeed_id, activity_id, link_type, description, img FROM newsfeed WHERE complete = 1 AND folder_id = {$folder->folder_id} ORDER BY newsfeed_id DESC LIMIT 4");
		while ($newsfeed = mysql_fetch_object($newsfeeds)) {
			$item = array(
                        'newsfeed_id'=>$newsfeed->newsfeed_id,
                        'link_type'=>$newsfeed->link_type,
            			'description_plain' => htmlentities(strip_tags($newsfeed->description)),
						'img' => null,
						'text' => null,
                       );
            if ($newsfeed->link_type == 'text') {
            	$link = mysql_fetch_object(mysql_query("SELECT content FROM links WHERE link_id = ".$newsfeed->activity_id));
            	if ($link) {
            		$item['text'] = $link->content;
            	}
            } else {
            	$item['img'] = $newsfeed->img;
            }
			$items[] = $item;
		}
		mysql_query("UPDATE folder SET recent_newsfeeds = '".mysql_real_escape_string(json_encode($items))."', flag = 1 WHERE folder_id = ".$folder->folder_id);
	}
	if (!$has_it) {
		echo "Done \r\n";
		break;
	}
}
