<?php
include_once dirname(__FILE__).'/../config.php';
include_once dirname(__FILE__).'/../db.php';
//RR simple queries are used because this is too much logic to do for the models

function get_folder($folder_id) {
	return mysql_fetch_object(mysql_query(
		"SELECT folder_id, user_id, CONCAT('/', users.uri_name, '/', folder_uri_name) as folder_url, folder_name
		FROM folder JOIN users ON (users.id = folder.user_id) 
		WHERE folder_id = ".$folder_id
	));
} 

echo "\n";

/*while (1) {
	$res = mysql_query("SELECT * FROM `notifications` WHERE flag = 0 AND (folder_id = 0 OR newsfeed_id = 0) ORDER BY id DESC LIMIT 100 ");
	$has_more = false;
	while ($row = mysql_fetch_object($res)) {
		echo "\n notification: ".$row->id;
		$has_more = true;
		$cache = json_decode($row->cache);
		
		if (!isset($cache->newsfeed)) {
			mysql_query("UPDATE notifications SET flag = 1 WHERE id = ".$row->id);
			continue;
		}
		
		$newsfeed_id = '';
		if (!$row->newsfeed_id) {
			$row->newsfeed_id = $cache->newsfeed->newsfeed_id;
			$newsfeed_id =  ', newsfeed_id = '.$row->newsfeed_id;
		}
		
		$folder_id = '';
		if (!$row->folder_id) {
			if (!$row->newsfeed_id) die("Folder not found ");
			$newsfeed = mysql_fetch_object(mysql_query("SELECT folder_id FROM newsfeed WHERE newsfeed_id = ".$row->newsfeed_id));
			if (!$newsfeed) {
				echo ' Newsfeed not found';
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
			$row->folder_id = $newsfeed->folder_id;
			$folder_id = ', folder_id = '.$row->folder_id;
		}
		
		if (!$newsfeed_id && !$folder_id) {
			mysql_query("UPDATE notifications SET flag = 1 WHERE id = ".$row->id);
			continue;
		}
		$sql = "UPDATE notifications SET flag = 1".$newsfeed_id.$folder_id." WHERE id = ".$row->id;
		mysql_query($sql);
		echo " updated";
	}
	if (!$has_more) break;
}

die("DONE");*/

//Update cache
while (1) {
	$res = mysql_query("SELECT * FROM `notifications` WHERE flag = 0 ORDER BY id DESC LIMIT 100 ");
	$has_more = false;
	while ($row = mysql_fetch_object($res)) {
		$has_more = true;
		echo "Notification: ".$row->id."\n";
		
		if ($row->item_id) {
			$model = get_instance()->notification_model->notification_types[$row->type];
			$model = get_instance()->{$model.'_model'};
			if (!$item = mysql_fetch_object(mysql_query("SELECT * FROM {$model->table()} WHERE ".$model->primary_key()." = ".$row->item_id))) {
				echo "Item not found 1!";
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
		} else {
			if ($row->type == 'follow' && !$row->a_id) {
				$activity_query = "SELECT * FROM activities WHERE user_id_from = $row->user_id_from AND user_id_to = $row->user_id_to AND type = 'connection'";
			} else {
				$activity_query = "SELECT * FROM activities WHERE id = ".$row->a_id;
			}
			
			if (!$activity = mysql_fetch_object(mysql_query($activity_query))) {
				echo "Activity not found!\n";
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
			
			$model = get_instance()->{$activity->type.'_model'};
			if (!$item = mysql_fetch_object(mysql_query("SELECT * FROM {$model->table()} WHERE ".$model->primary_key()." = ".$activity->activity_id))) {
				echo "Item not found!\n";
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
			
		}
		
		if (!$user = mysql_fetch_object(mysql_query("SELECT id, uri_name, first_name, last_name, avatar FROM users WHERE id = ".$row->user_id_from))) {
			echo "User not found!\n";
			mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
			continue;
		}
		
		$newsfeed_id = 0;
		if ($row->newsfeed_id) $newsfeed_id = $row->newsfeed_id;
		if (!$newsfeed_id && isset($item->newsfeed_id)) $newsfeed_id = $item->newsfeed_id;
		
		$folder_id = 0;
		
		if ($row->type == 'link_comm_like') {
			if (!$comment = mysql_fetch_object(mysql_query("SELECT comment, newsfeed_id, folder_id FROM comments WHERE comment_id = ".$item->comment_id))) {
				echo "Comment not found!\n";
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
			$newsfeed_id = $comment->newsfeed_id;
			$folder_id = $comment->folder_id; 
		}
		
		if ($newsfeed_id) {
			if (!$newsfeed = mysql_fetch_object(mysql_query("SELECT newsfeed_id, folder_id, link_type, description, url, img, complete FROM newsfeed WHERE newsfeed_id = ".$newsfeed_id))) {
				echo "Newsfeed not found! \n";
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
		}
		
		if ($row->folder_id) $folder_id = $row->folder_id;
		if (!$folder_id && $newsfeed_id) $folder_id = $newsfeed->folder_id;
		if (!$folder_id && isset($item->folder_id)) $folder_id = $item->folder_id;
		
		if ($folder_id) {
			if (!$folder = get_folder($folder_id)) {
				echo "Folder not found! \n";
				mysql_query("DELETE FROM notifications WHERE id = ".$row->id);
				continue;
			}
		}
		
		$cache = array(
			'user' => array(
				'url' => '/'.$user->uri_name,
				'full_name' => $user->first_name.' '.$user->last_name,
				'avatar' => $user->avatar
			)
		);
		
		if (in_array($row->type, array('folder_contributor','newsfeed','collaboration_newsfeed','follow_folder','folder_like',
			'link_like', 'photo_like', 'link_comm_like', 'u_comm')
		)) {
			$cache['folder'] = array(
				'folder_url' => $folder->folder_url,
				'folder_name' => $folder->folder_name,
			);
		}
		
		if (in_array($row->type, array('link_like','photo_like','link_comm_like','u_comm','at_comm','at_drop'))) {
			$cache['newsfeed'] = array(
				'newsfeed_id' => $newsfeed->newsfeed_id,
				'link_type' => $newsfeed->link_type,
				'description' => $newsfeed->description,
				'url' => $newsfeed->url,
				'img' => $newsfeed->img,
				'complete' => $newsfeed->complete,
			);
		}
		
		if ($row->type == 'badge') {
			$cache['badge'] = array(
				'name' => $item->name,
			);
		} elseif ($row->type == 'message') {
			$cache['msg_content'] = array(
				'msg_body' => $item->msg_body,
				'thread_id' => $item->thread_id,
			);
		} elseif ($row->type == 'u_comm') {
			$cache['comment'] = array(
				'comment' => $item->comment,
			);
		} elseif ($row->type == 'link_comm_like') {
			$cache['comment'] = array(
				'comment' => $comment->comment,
			);
		}

		if (isset($cache['newsfeed']))	{
			$newsfeed_id = $cache['newsfeed']['newsfeed_id'];
		}

		$cache = mysql_real_escape_string(json_encode($cache));
		if (!mysql_query("UPDATE notifications SET item_id = ".$item->{$model->primary_key()}.", cache = '".$cache."', flag = 1, `newsfeed_id` = '{$newsfeed_id}', `folder_id` = '{$folder_id}' WHERE id = ".$row->id)) {
			echo mysql_error();
		}
		
	}
	
	if (!$has_more) {
		echo "DONE\n"; break;
	}
}