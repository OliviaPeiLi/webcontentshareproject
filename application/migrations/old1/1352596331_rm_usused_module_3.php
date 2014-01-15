<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rm_usused_module_3 extends CI_Migration {

	public function up()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='foler_filter'");
		mysql_query("DELETE FROM `modes_config` WHERE name='follow_from_invite'");
		mysql_query("DELETE FROM `modes_config` WHERE name='atmention_notification'");
		mysql_query("DELETE FROM `modes_config` WHERE name='fb_comment_action'");
		mysql_query("DELETE FROM `modes_config` WHERE name='fb_view_action'");
		mysql_query("DELETE FROM `modes_config` WHERE name='fb_drop_object'");
		mysql_query("DELETE FROM `modes_config` WHERE name='mentions_page'");
		mysql_query("DELETE FROM `modes_config` WHERE name='replacable_thumbnails'");
		mysql_query("DELETE FROM `modes_config` WHERE name='trending_categories_bar'");
	}

	public function down()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('folder_filter',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('follow_from_invite',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('atmention_notification',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('fb_comment_action',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('fb_view_action',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('fb_drop_object',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('mentions_page',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('replacable_thumbnails',  '1',  '1',  '1')");
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('trending_categories_bar',  '1',  '1',  '1')");
	}
}
