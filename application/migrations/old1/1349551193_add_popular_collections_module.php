<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_popular_collections_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('popular_collections',  '1',  '0',  '0')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='popular_collections'");
	}
}
