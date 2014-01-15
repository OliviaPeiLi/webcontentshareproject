<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_twitter_share_count extends CI_Migration {

	public function up()
	{
		mysql_query('ALTER TABLE folder ADD twitter_share_count INT(10) UNSIGNED NOT NULL DEFAULT 0');
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
