<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_folderlike_emailsettings extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `email_settings` ADD  `folder_like`  ENUM(  '1',  '0' )  NOT NULL AFTER  `follow_list`");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
