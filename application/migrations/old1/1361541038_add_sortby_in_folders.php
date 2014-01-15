<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sortby_in_folders extends CI_Migration {

	public function up()
	{
		
		mysql_query("ALTER TABLE  `folder` ADD  `sort_by` TINYINT UNSIGNED NOT NULL");
	}

	public function down()
	{
		
	}
}
