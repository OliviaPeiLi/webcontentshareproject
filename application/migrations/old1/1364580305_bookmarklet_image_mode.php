<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Bookmarklet_image_mode extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('bookmarklet_image_mode', 1, 1, 0, 'Introduce the new bookmatklet image mode')");
	}

	public function down()
	{
		mysql_query("DELETE FROM modes_config WHERE `name` = 'bookmarklet_image_mode'");
	}
}
