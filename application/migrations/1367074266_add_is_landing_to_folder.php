<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_is_landing_to_folder extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `folder` ADD `is_landing` TINYINT UNSIGNED NOT NULL,
					ADD INDEX ( `is_landing` )");
		mysql_query("UPDATE folder SET is_landing = 1 ORDER BY `ranking` DESC LIMIT 7");
	}

	public function down()
	{
		
	}
}
