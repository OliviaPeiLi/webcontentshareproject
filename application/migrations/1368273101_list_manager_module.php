<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_List_manager_module extends CI_Migration {

	public function up()
	{		
		mysql_query("INSERT INTO modes_config (name, development, description) VALUES ('list_manager', 1, 'Enable access to user list manager')");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
