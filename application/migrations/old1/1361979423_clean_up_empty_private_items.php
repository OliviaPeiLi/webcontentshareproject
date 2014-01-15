<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_up_empty_private_items extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE folder SET private = '0' WHERE private = ''");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
