<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_design_ugc_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('design_ugc', 0, 0, 0, 'Complete design - User Generated content')");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
