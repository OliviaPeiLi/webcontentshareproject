<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_temp_scripts extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `scripts` (`name` ,`num_instances`) VALUES ('temp/link_watermark.php',  '1')");
	}

	public function down()
	{
		mysql_query("DELETE FROM `scripts` WHERE name = 'temp/link_watermark.php'");
	}
}
