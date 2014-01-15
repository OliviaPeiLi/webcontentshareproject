<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newsfeed_referrals extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed_referrals` ADD  `views` INT UNSIGNED NOT NULL,
					ADD  `updated_at` DATETIME NOT NULL");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
