<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Cleanup_signup extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('fb_friends');
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
