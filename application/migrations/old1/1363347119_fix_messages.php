<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_messages extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `msg_info` CHANGE  `erase_type`  `erase_type` TINYINT( 1 ) NOT NULL,
					CHANGE  `display_status`  `display_status` TINYINT( 1 ) NOT NULL ,
					CHANGE  `number_read`  `number_read` TINYINT( 1 ) NOT NULL");
		mysql_query("UPDATE `msg_info` SET `erase_type` = `erase_type` - 1, `display_status` = `display_status` - 1, `number_read` = `number_read` - 1");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
