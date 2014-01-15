<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_folder_to_comments extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `comments` ADD `folder_id` INT UNSIGNED NOT NULL AFTER  `newsfeed_id` ,
					ADD INDEX (`folder_id`)");
		mysql_query("UPDATE comments SET folder_id = (SELECT folder_id FROM newsfeed WHERE newsfeed.newsfeed_id = comments.newsfeed_id)");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
