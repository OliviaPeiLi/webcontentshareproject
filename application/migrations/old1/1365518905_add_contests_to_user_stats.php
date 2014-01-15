<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contests_to_user_stats extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `user_stats` ADD  `contests` INT UNSIGNED NOT NULL AFTER  `ref_count`");
		mysql_query("UPDATE `user_stats` SET contests = (SELECT COUNT(id) FROM contests WHERE contests.user_id = user_stats.user_id)");
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'contests');
	}
}
