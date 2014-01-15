<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Landing_ugc_module extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('landing_ugc', 1, 0, 0, 'Landing page - User Generated content')");
		mysql_query("DELETE FROM `modes_config` WHERE `name` IN ('new_landing_page','landing_page_2col','landing_page_type','fresh_landing_page','wide_tile_landing_page','postcard_landing_page','landing_header')");
	}

	public function down()
	{
		
	}
}
