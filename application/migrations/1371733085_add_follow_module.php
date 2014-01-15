<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_follow_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO modes_config (name, development, description) VALUES ('follow', 0, 'Enable user follow feature')");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
