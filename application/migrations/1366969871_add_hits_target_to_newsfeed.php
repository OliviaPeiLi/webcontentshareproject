<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_hits_target_to_newsfeed extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` CHANGE `up_target` `up_target` INT UNSIGNED NOT NULL AFTER `share_target`");
		mysql_query("ALTER TABLE  `newsfeed` ADD `hits_target` INT UNSIGNED NOT NULL AFTER `up_target`");		
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
