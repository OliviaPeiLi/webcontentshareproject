<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_wide_tile_landing_page extends CI_Migration {

	public function up()
	{
		  mysql_query("INSERT INTO  `fantoon_ci`.`modes_config` (`name` ,`development` ,`staging` ,`production`)
						VALUES 

							('wide_tile_landing_page',  '1',  '0',  '0');
		  ");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
