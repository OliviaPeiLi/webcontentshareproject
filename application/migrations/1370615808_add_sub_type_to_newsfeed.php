<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_sub_type_to_newsfeed extends CI_Migration {

	public function up()
	{
		//Used together with folder->filters field
		mysql_query("ALTER TABLE  `newsfeed` ADD  `sub_type` TINYINT UNSIGNED NOT NULL AFTER  `link_type_id` ,
					ADD INDEX (  `sub_type` )");
	}

	public function down()
	{
		
	}
}
