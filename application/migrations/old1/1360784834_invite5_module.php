<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Invite5_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('invite5', 1, 1, 1, 'requires for at least 5 invited users to gain bookmarklet access')");
	}

	public function down()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` = 'invite5'");
	}
}
