<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_db1 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('user_stats','fb_links');
		$this->dbforge->drop_column('user_stats','twitter_links');
		mysql_query("ALTER TABLE `user_stats` CHANGE `mention` `mentions_count` INT UNSIGNED NOT NULL DEFAULT '0'");
		mysql_query("UPDATE user_stats SET mentions_count = (
			SELECT COUNT(id) FROM mentions JOIN newsfeed ON (newsfeed.newsfeed_id = mentions.newsfeed_id) WHERE user_id_to = user_id
		)");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
