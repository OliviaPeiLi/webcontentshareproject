<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_email_to_newsfeed_shares extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `email_share_count` INT UNSIGNED NOT NULL AFTER  `gplus_share_count`");
		mysql_query("ALTER TABLE  `newsfeed_shares` CHANGE `api` `api` ENUM('fb','twitter','pinterest','gplus','linkedin','email') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
