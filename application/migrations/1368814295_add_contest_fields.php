<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contest_fields extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `top_prize` VARCHAR(255) NOT NULL AFTER  `sxsw_email` ,
					ADD  `share_goal` INT NOT NULL AFTER  `top_prize`");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
