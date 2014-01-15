<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sharemail_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('email_share', 1, 1, 0, 'Share email button')");
	}

	public function down()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` = 'email_share'");
	}

}
