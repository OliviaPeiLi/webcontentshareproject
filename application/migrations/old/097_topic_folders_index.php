<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_topic_folders_index extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `topic_folders` ADD INDEX (  `folder_id` )");
		mysql_query("ALTER TABLE  `topic_folders` ADD INDEX (  `topic_id` )");
	}

	public function down()
	{
		mysql_query("ALTER TABLE topic_folders DROP INDEX folder_id");
		mysql_query("ALTER TABLE topic_folders DROP INDEX topic_id");
	}
}
