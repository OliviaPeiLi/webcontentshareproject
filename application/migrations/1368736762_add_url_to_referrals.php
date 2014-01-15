<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_url_to_referrals extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed_referrals` ADD  `url` VARCHAR(244) NOT NULL AFTER  `name`");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
