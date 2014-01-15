<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Folder_name_length extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` CHANGE  `folder_name`  `folder_name` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
