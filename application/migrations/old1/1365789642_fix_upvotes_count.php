<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_upvotes_count extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `user_stats` CHANGE `likes` `upvotes_count` INT UNSIGNED NOT NULL DEFAULT '0'");
		mysql_query("ALTER TABLE `user_stats` CHANGE `up_got` `upvotes_got_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `upvotes_count`");
		mysql_query("UPDATE user_stats SET upvotes_count = (SELECT COUNT(like_id) FROM likes WHERE likes.user_id = user_stats.user_id)");
		
		$res = mysql_query("SELECT * FROM  `likes` WHERE newsfeed_id =0 AND comment_id =0 AND folder_id =0");
		while ($row = mysql_fetch_object($res)) {
			$newsfeed = false;
			if ($row->photo_id) {
				$newsfeed = mysql_fetch_object(mysql_query("SELECT newsfeed_id FROM newsfeed WHERE activity_id = ".$row->photo_id));
			} elseif ($row->link_id) {
				$newsfeed = mysql_fetch_object(mysql_query("SELECT newsfeed_id FROM newsfeed WHERE activity_id = ".$row->link_id));
			}
			if ($newsfeed) {
				mysql_query("UPDATE likes SET newsfeed_id = {$newsfeed->newsfeed_id} WHERE like_id = ".$row->like_id);
			} else {
				mysql_query("DELETE FROM likes WHERE like_id = ".$row->like_id);
			}
		}
		$this->dbforge->drop_column('likes', 'photo_id');
		$this->dbforge->drop_column('likes', 'link_id');
		
		mysql_query("ALTER TABLE  `likes` ADD  `user_id_to` INT UNSIGNED NOT NULL AFTER `user_id`, ADD INDEX (`user_id_to`), ADD INDEX (`folder_id`)");
		
		mysql_query("UPDATE likes SET user_id_to = 
						(SELECT user_id_from FROM newsfeed WHERE newsfeed_id = likes.newsfeed_id)
						WHERE newsfeed_id > 0
					");
		mysql_query("UPDATE likes SET user_id_to = 
						(SELECT user_id_from FROM comments WHERE comment_id = likes.comment_id)
						WHERE comment_id > 0
					");
		mysql_query("UPDATE likes SET user_id_to = 
						(SELECT user_id FROM folder = WHERE folder_id = likes.folder_id)
						WHERE folder_id > 0
					");

		mysql_query("UPDATE user_stats SET upvotes_got_count = (SELECT COUNT(like_id) FROM likes WHERE user_id_to = user_stats.user_id)");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
