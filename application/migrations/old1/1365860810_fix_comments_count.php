<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_comments_count extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `user_stats` CHANGE `comments` `comments_count` INT UNSIGNED NOT NULL DEFAULT '0'");
		mysql_query("ALTER TABLE `user_stats` CHANGE `comment_got` `comments_got_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `comments_count`");
		mysql_query("UPDATE comments SET newsfeed_id = (SELECT newsfeed_id FROM newsfeed WHERE activity_id = comments.photo_id) WHERE comments.newsfeed_id = 0 AND comments.photo_id > 0");
		mysql_query("UPDATE comments SET newsfeed_id = (SELECT newsfeed_id FROM newsfeed WHERE activity_id = comments.link_id) WHERE comments.newsfeed_id = 0 AND comments.link_id > 0");
		
		$this->dbforge->drop_column('comments', 'photo_id');
		$this->dbforge->drop_column('comments', 'link_id');
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
