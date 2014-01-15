<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_indexes_to_newsfeed extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `fantoon_ci`.`newsfeed` ADD INDEX (  `activity_id` )");
		mysql_query("ALTER TABLE  `fantoon_ci`.`newsfeed` ADD INDEX (  `type` )");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `links` DROP INDEX  `activity_id` ;");
		mysql_query("ALTER TABLE  `links` DROP INDEX  `type` ;");
	}
}
