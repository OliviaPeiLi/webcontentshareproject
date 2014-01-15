<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Optimize_notifications extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `notifications` CHANGE  `type`  `type` ENUM(  'folder_contributor',  'newsfeed',  'collaboration_newsfeed',  'follow',  'follow_folder',  'badge',  'message',  'link_like', 'photo_like',  'link_comm_like',  'folder_like',  'u_comm',  'at_comm',  'at_drop' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		mysql_query("ALTER TABLE  `notifications` ADD `item_id` INT UNSIGNED NOT NULL AFTER  `type`");
		
		mysql_query("UPDATE `notifications` SET item_id = m_id WHERE m_id > 0");
		$this->dbforge->drop_column('notifications', 'm_id');
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
