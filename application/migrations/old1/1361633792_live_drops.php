<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Live_drops extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('live_drops', 1, 1, 0, 'Introduce the new live_drops type')");
	}

	public function down()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` = 'live_drops'");
	}
}
