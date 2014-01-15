<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_solr_search_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('solr_search',  '1',  '0',  '0')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='signup_wait_list'");
	}
}
