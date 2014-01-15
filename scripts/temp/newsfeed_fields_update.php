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


function get_comments_cache($type, $id, $config){
	$res = mysql_query("SELECT comment_id, comments.time, comment, user_id_from
					 	FROM comments
					 	WHERE ".$type."_id = '".$id."'
					 	ORDER BY comment_id DESC
					 	LIMIT 10");
					 	//die();
	$comment_array = array();
	if ($res) {
		while ($comment = mysql_fetch_object($res)){
			if($comment->user_id_from > 0){
				$user = get_users_data($comment->user_id_from);
				if($user->avatar == ''){
					$user_avatar = $config['s3_url'].'/users/default/blue_small.png';	
				}else{
					$name = substr($user->avatar, 0, strrpos($user->avatar, '.'));
					$ext = substr($user->avatar, strrpos($user->avatar, '.'));
					$user_avatar = $config['s3_url'].'/users/'.$name.'_small'.$ext;
				}
				$user_name = $user->first_name.' '.$user->last_name;
				$user_url = $config['base_url'].$user->uriname;
				
				$comment_data['comment_id'] = $comment->comment_id;
				$comment_data['time'] = $comment->time;
				$comment_data['comment'] = $comment->comment;
				$comment_data['user_from_avatar'] = $user_avatar;
				$comment_data['user_from_name'] = $user_name;
				$comment_data['user_from_url'] = $user_url;
				$comment_data['user_id_from'] = $comment->user_id_from;
				
				$comment_array[] = $comment_data;
			}
		}
	}
	
	$comment_cache = json_encode(array_reverse($comment_array));
	return $comment_cache;
}

function get_users_data($id){
	$res = mysql_query("SELECT id, first_name, last_name, uri_name, avatar
					 	FROM users
					 	WHERE id = '".$id."'");
	return mysql_fetch_object($res);
}

function get_activity($type, $id){
	if($type == 'photo'){
		$res = mysql_query("SELECT caption, img, img_width, img_height
							FROM photos 
							WHERE photo_id = '".$id."'");
	}
	if($type == 'link'){
		$res = mysql_query("SELECT title, text, img, img_width, img_height
							FROM links 
							WHERE link_id = '".$id."'");
	}
	if($res){
		return mysql_fetch_object($res);
	}
}


while (1) {
						
	$res = mysql_query("SELECT newsfeed_id, activity_id, type
						FROM newsfeed
						WHERE thumb_generated = 0
						ORDER BY newsfeed_id DESC
						LIMIT 2000");
						
	while ($row = mysql_fetch_object($res)) {
//	var_dump($row);
//	die();
/*
		$activity = get_activity($row->type, $row->activity_id);
		
		if (!$activity->img) {
			mysql_query("UPDATE newsfeed SET img = 'N/A' WHERE newsfeed_id='{$row->newsfeed_id}'");
			continue;
		}
		
		if($row->type == 'photo'){
			$title = $description = $activity->caption;
		}
		if($row->type == 'link'){
			$title = $activity->title;
			$description = $activity->text;
		}
		
		$sql = "UPDATE newsfeed 
					 SET img='{$activity->img}',
					 	img_width = '{$activity->img_width}',
					 	img_height = '{$activity->img_height}',
					 	title='".mysql_real_escape_string($title)."', 
					 	description='".mysql_real_escape_string($description)."', 
					 	comments_cache='".get_comments_cache($row->type, $row->activity_id, $config)."',
					 	thumb_generated = 1
					 WHERE newsfeed_id='{$row->newsfeed_id}'";
*/					 
		$sql = "UPDATE newsfeed 
					 SET  
					 	comments_cache='".mysql_real_escape_string(get_comments_cache($row->type, $row->activity_id, $config))."',
					 	thumb_generated = 1
					 WHERE newsfeed_id='{$row->newsfeed_id}'";
						
		if ( ! mysql_query($sql)) {
			echo $sql."\r\n";
			echo mysql_error();
			die();
		}
		
		echo "\r\n Update newsfeed {$row->newsfeed_id}";		
	}		
	//sleep(1);
}
