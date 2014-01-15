<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_topic_id_to_folders extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` ADD  `topic_id` INT UNSIGNED NOT NULL AFTER  `user_id`");
		mysql_query("UPDATE folder SET topic_id = (SELECT topic_id FROM topic_folders WHERE folder_id = folder.folder_id LIMIT 1)");
	}

	public function down()
	{
		mysql_query("ALTER TABLE `folder` DROP `topic_id`");
	}
}
