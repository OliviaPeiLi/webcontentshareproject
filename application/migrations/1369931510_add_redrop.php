<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_redrop extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `notifications` CHANGE  `type`  `type` ENUM(  'folder_contributor',  'newsfeed',  'collaboration_newsfeed',  'follow',  'follow_folder',  'badge',  'message',  'link_like',  'photo_like',  'link_comm_like',  'folder_like',  'u_comm', 'at_comm',  'at_drop',  'redrop' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
