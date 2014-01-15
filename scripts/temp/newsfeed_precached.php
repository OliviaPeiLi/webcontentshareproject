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
	$i = 0;
	$res = mysql_query("SELECT newsfeed_id, user_id_from, activity_id, type, orig_user_id FROM newsfeed WHERE thumb_generated=0 ORDER BY newsfeed_id DESC LIMIT 5000");
	
	while ($newsfeed = mysql_fetch_assoc($res)) {
		$i++;
		//echo $i.' ';
		if(!$newsfeed) break;
		if($newsfeed['orig_user_id'] == 0) $newsfeed['orig_user_id'] = $newsfeed['user_id_from'];
		
		//user_drops
		$user_drops_res = mysql_query("SELECT newsfeed_id, img, link_type, activity_id FROM newsfeed WHERE user_id_from=".$newsfeed['orig_user_id']." AND complete='1' AND newsfeed_id!=".$newsfeed['newsfeed_id']." ORDER BY newsfeed_id DESC LIMIT 4");
		while($user_drops_data = mysql_fetch_object($user_drops_res)){
			if($user_drops_data){
				$drop = $user_drops_data;	
				list($filename, $ext) = explode('.', $drop->img); 
				$ext = '.'.$ext;
				$img = $filename ? $config['s3_url'].'/links/'.$filename.'_square'.$ext : '';
				if($filename == 'N/A'){
					$img = '';
				}
				if($drop->link_type == 'text'){
					$links = mysql_query("SELECT content FROM links WHERE link_id=".$drop->activity_id);
					$link = mysql_fetch_object($links);
					$content = $link->content;
					$img = $content;
				}
		
				$user_drops[$drop->newsfeed_id] = array('newsfeed_id'=>$drop->newsfeed_id,
		    										    'newsfeed_url'=>'http://www.fandrop.com/drop/'.$drop->newsfeed_id,
		    										    'newsfeed_img'=>$img,
		    										    'link_type'=>$drop->link_type);
			}
		}
		//source_drops
		if($newsfeed['type'] == 'link'){
			$links = mysql_query("SELECT source FROM links WHERE link_id=".$newsfeed['activity_id']);
			$link = mysql_fetch_object($links);
			$source = $link->source;
			$source_drops = array();
			if($source != ''){
				$source_links_res = mysql_query("SELECT link_id FROM links WHERE source='".$source."' AND link_id!=".$newsfeed['activity_id']." ORDER BY link_id DESC LIMIT 4");
				while($source_links = mysql_fetch_object($source_links_res)){
					if($source_links){
						$link_ids[] = $source_links->link_id;
					}
				}
				
				if(@$link_ids && !empty($link_ids)){
					$link_ids = '('.implode(",", $link_ids).')';
					$source_drops_res = mysql_query("SELECT newsfeed_id, img, link_type, activity_id FROM newsfeed WHERE type= 'link' AND complete='1' AND activity_id IN ".$link_ids." ORDER BY newsfeed_id DESC LIMIT 4");
					//var_dump("SELECT newsfeed_id, img FROM newsfeed WHERE type= 'link' AND activity_id IN ".$link_ids);
					//die();
					while($source_drops_data = mysql_fetch_object($source_drops_res)){
		
						if($source_drops_data){
							$drop = $source_drops_data;
							$source_drops['source'] = $source;
							
							list($filename, $ext) = explode('.', $drop->img); 
							$ext = '.'.$ext;
							$img = $filename ? $config['s3_url'].'/links/'.$filename.'_square'.$ext : '';
							if($filename == 'N/A'){
								$img = '';
							}
							if($drop->link_type == 'text'){
								$links = mysql_query("SELECT content FROM links WHERE link_id=".$drop->activity_id);
								$link = mysql_fetch_object($links);
								$content = $link->content;
								$img = $content;
							}
					
							$source_drops['drops'][$drop->newsfeed_id] = array('newsfeed_id'=>$drop->newsfeed_id,
					    										    'newsfeed_url'=>'http://www.fandrop.com/drop/'.$drop->newsfeed_id,
					    										    'newsfeed_img'=>$img,
					    										    'link_type'=>$drop->link_type);
						}
					}
				}
			}
		}
		
		mysql_query("UPDATE newsfeed SET user_drops = '".@json_encode($user_drops)."', source_drops = '".@json_encode($source_drops)."', thumb_generated = 1 WHERE newsfeed_id = '".$newsfeed['newsfeed_id']."'");
		unset($link_ids);
		unset($drop);
		unset($user_drops);
		unset($source_drops);
		//die(var_dump($newsfeed['newsfeed_id']));

	}

	//die('5000 done');
	sleep(30);
	echo 'one load is done';
	
	
}

