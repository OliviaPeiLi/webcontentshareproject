<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rm_usused_module extends CI_Migration {

	public function up()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='sxsw'");
		mysql_query("DELETE FROM `modes_config` WHERE name='topics'");
		mysql_query("DELETE FROM `modes_config` WHERE name='topics_add_new'");
		mysql_query("DELETE FROM `modes_config` WHERE name='folders'");
		mysql_query("DELETE FROM `modes_config` WHERE name='interests'");
		mysql_query("DELETE FROM `modes_config` WHERE name='loops'");
		mysql_query("DELETE FROM `modes_config` WHERE name='embed_collection'");
	}

	public function down()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('sxsw',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('topics',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('topics_add_new',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('folders',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('interests',  '0',  '0',  '0')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('loops',  '0',  '0',  '0')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('embed_collection',  '1',  '1',  '1')");
	}
}
