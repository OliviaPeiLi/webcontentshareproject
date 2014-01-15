<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_user_follows_counts extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('request_connection');
		
		mysql_query("ALTER TABLE  `user_stats` ADD  `followers_count` INT UNSIGNED NOT NULL AFTER  `comments_got_count`");
		mysql_query("ALTER TABLE  `user_stats` ADD  `followings_count` INT UNSIGNED NOT NULL AFTER  `followers_count`");
		
		mysql_query("UPDATE user_stats SET followers_count = (SELECT follower FROM users WHERE users.id = user_stats.user_id)");
		mysql_query("UPDATE user_stats SET followings_count = (SELECT following FROM users WHERE users.id = user_stats.user_id)");
		
		$this->dbforge->drop_column('users', 'follower');
		$this->dbforge->drop_column('users', 'following');
	}

	public function down()
	{
		
	}
}
