<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_enablefulldroppage_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('enable_fullsraper', 1, 1, 0, 'Enable / Disable Fullscraper')");
	}

	public function down()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` = 'enable_fullsraper'");
	}

}
