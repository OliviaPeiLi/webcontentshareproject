<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_collections_count extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE `user_stats` CHANGE `collections` `public_collections_count` INT UNSIGNED NOT NULL DEFAULT '0'");
		mysql_query("ALTER TABLE `user_stats` CHANGE `public_collections` `private_collections_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `public_collections_count`");
		mysql_query("ALTER TABLE `user_stats` CHANGE `contests` `contests_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `private_collections_count`");
		mysql_query("UPDATE user_stats SET public_collections_count = (SELECT COUNT(folder_id) FROM folder WHERE folder.user_id = user_stats.user_id AND private = '0' AND contest_id = 0)");
		mysql_query("UPDATE user_stats SET private_collections_count = (SELECT COUNT(folder_id) FROM folder WHERE folder.user_id = user_stats.user_id AND private = '1'  AND contest_id = 0)");
		mysql_query("UPDATE user_stats SET contests_count = (SELECT COUNT(id) FROM contests WHERE contests.user_id = user_stats.user_id)");
	}

	public function down()
	{
		
	}
}
