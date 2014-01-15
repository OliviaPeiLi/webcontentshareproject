<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rm_usused_module_2 extends CI_Migration {

	public function up()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='social_share'");
		mysql_query("DELETE FROM `modes_config` WHERE name='fixed_tooltip'");
		mysql_query("DELETE FROM `modes_config` WHERE name='redrop_comment_social_share'");
		mysql_query("DELETE FROM `modes_config` WHERE name='new_invite'");
		mysql_query("DELETE FROM `modes_config` WHERE name='yahoo_invite'");
	}

	public function down()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('fixed_tooltip',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('social_share',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('redrop_comment_social_share',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('new_invite',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('yahoo_invite',  '1',  '1',  '1')");
	}
}
