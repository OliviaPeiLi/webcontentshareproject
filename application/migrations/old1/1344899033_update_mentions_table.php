<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_mentions_table extends CI_Migration {

	public function up()
	{
		$res = mysql_query("SELECT user_id_from, user_id_to, a_id 
								FROM notifications 
								WHERE type = 'at_comm'
							");
		while ($row = mysql_fetch_object($res)) {
			
			$query = mysql_query("SELECT id, activity_id
									FROM activities 
									WHERE id = $row->a_id
									LIMIT 1
								");
			$activity = mysql_fetch_object($query);
			
			$query = mysql_query("SELECT comment_id, link_id, photo_id, time
									FROM comments 
									WHERE comment_id = $activity->activity_id
									LIMIT 1
								");
			$comment = mysql_fetch_object($query);

			if($comment->link_id>0){
				$query = mysql_query("SELECT newsfeed_id
										FROM newsfeed
										WHERE (activity_id = $comment->link_id AND type = 'link')
										LIMIT 1
									");
				$newsfeed = mysql_fetch_object($query);
				mysql_query("INSERT INTO mentions (user_id_from, user_id_to, newsfeed_id) 
								VALUES ('".$row->user_id_from."', '".$row->user_id_to."', '".$newsfeed->newsfeed_id."')");
				
			}elseif($comment->photo_id>0){
				$query = mysql_query("SELECT newsfeed_id
										FROM newsfeed
										WHERE (activity_id = $comment->photo_id AND type = 'photo')
										LIMIT 1
									");
				$newsfeed = mysql_fetch_object($query);
				mysql_query("INSERT INTO mentions (user_id_from, user_id_to, newsfeed_id) 
								VALUES ('".$row->user_id_from."', '".$row->user_id_to."', '".$newsfeed->newsfeed_id."')");
			}
		}
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
