<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Improove_contests_logic extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` ADD  `contest_id` INT UNSIGNED NOT NULL AFTER  `user_id`,
					ADD INDEX (  `contest_id` )");
		mysql_query("UPDATE folder SET contest_id = user_id WHERE type = 2");
		mysql_query("UPDATE folder SET user_id = (SELECT user_id FROM contests WHERE id = contest_id) WHERE type =2");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
