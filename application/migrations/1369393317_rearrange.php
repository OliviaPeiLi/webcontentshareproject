<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rearrange extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `position` INT NOT NULL AFTER  `parent_id` ,
					ADD INDEX (  `position` )");
		
		mysql_query("ALTER TABLE  `folder` ADD  `position` INT NOT NULL AFTER  `folder_id` ,
					ADD INDEX (  `position` )");
		
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
