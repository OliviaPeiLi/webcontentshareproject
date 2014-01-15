<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Forget_password extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `users` ADD  `key` VARCHAR(10) NOT NULL AFTER `password` ");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
