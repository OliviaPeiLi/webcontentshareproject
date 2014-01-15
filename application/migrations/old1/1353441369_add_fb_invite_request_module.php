<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_fb_invite_request_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name` ,`development` ,`staging` ,`production`) VALUES ('fb_invite_request',  '1',  '0',  '0')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name='fb_invite_request'");
	}
}
