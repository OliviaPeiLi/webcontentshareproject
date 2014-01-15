<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_filters_to_folder extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `folder` ADD `filters` TEXT NOT NULL AFTER `recent_newsfeeds`");
	}

	public function down()
	{

	}
}
