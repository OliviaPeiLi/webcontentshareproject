<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_folder_id_to_mentions extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `mentions` ADD  `folder_id` INT UNSIGNED NOT NULL AFTER  `newsfeed_id`");
		mysql_query("UPDATE mentions SET folder_id = (SELECT folder_id FROM newsfeed WHERE newsfeed.newsfeed_id = mentions.newsfeed_id)");
		mysql_query("DELETE FROM mentions WHERE folder_id = 0");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
