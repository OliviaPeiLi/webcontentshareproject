<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Optimize_users_table extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `users` CHANGE  `fb_id`  `fb_id` BIGINT( 20 ) NOT NULL DEFAULT  '0',
										  CHANGE  `twitter_id`  `twitter_id` BIGINT( 20 ) NOT NULL DEFAULT  '0'");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
