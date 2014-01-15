<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_flag_to_notifications extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `notifications` ADD `flag` TINYINT UNSIGNED NOT NULL,
					ADD INDEX ( `flag` )");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
