<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_postcard_landing_page_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('postcard_landing_page',  '1',  '0',  '0')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='postcard_landing_page'");
	}
}
