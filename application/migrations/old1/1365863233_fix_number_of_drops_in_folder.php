<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_number_of_drops_in_folder extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `folder` CHANGE `drops` `newsfeeds_count` INT UNSIGNED NOT NULL DEFAULT '0'");
		mysql_query("ALTER TABLE `folder` CHANGE `followers` `followers_count` INT UNSIGNED NOT NULL DEFAULT '0'");
		mysql_query("ALTER TABLE `folder` CHANGE `fb_share_count` `fb_share_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `followers_count`");
		mysql_query("ALTER TABLE `folder` CHANGE `up_count` `upvotes_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `fb_share_count`");
		mysql_query("ALTER TABLE  `folder` CHANGE  `folder_uri_name`  `folder_uri_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' AFTER `folder_name`");
		
		$this->dbforge->drop_column('folder', 'topic_id');
		
		mysql_query("UPDATE folder SET newsfeeds_count = (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE newsfeed.folder_id = folder.folder_id)");
		mysql_query("UPDATE folder SET upvotes_count = (SELECT COUNT(like_id) FROM likes WHERE likes.folder_id = folder.folder.id)");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
