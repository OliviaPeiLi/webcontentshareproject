<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_folder_to_notifications extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `notifications` ADD `folder_id` INT UNSIGNED NOT NULL DEFAULT  '0' AFTER  `newsfeed_id`");
		mysql_query("UPDATE notifications SET folder_id = (SELECT folder_id FROM newsfeed WHERE newsfeed.newsfeed_id = notifications.newsfeed_id) WHERE newsfeed_id > 0");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
