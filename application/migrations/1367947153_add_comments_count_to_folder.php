<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_comments_count_to_folder extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `folder` CHANGE  `twitter_share_count`  `twitter_share_count` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `fb_share_count`");
		
		mysql_query("ALTER TABLE  `folder` ADD  `comments_count` INT UNSIGNED NOT NULL AFTER  `twitter_share_count`");
		//mysql_query("UPDATE folder SET comments_count = (SELECT COUNT(comment_id) FROM comments WHERE comments.folder_id = folder.folder_id)");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
