<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contests_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('contests', 1, 1, 0, 'User custom created contests')");
	}

	public function down()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` = 'contests'");
	}
}
