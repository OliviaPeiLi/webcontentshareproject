<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_New_theme extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (name) VALUES ('new_theme');");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name = 'new_theme'");
	}
}
