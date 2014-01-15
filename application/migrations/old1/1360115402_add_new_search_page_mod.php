<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_new_search_page_mod extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('new_search_page',  '1',  '0',  '0')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='new_search_page'");
	}
}
