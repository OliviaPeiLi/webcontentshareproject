<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_db extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('user_stats', 'drops');
		$this->dbforge->drop_column('user_stats', 'image');
		$this->dbforge->drop_column('user_stats', 'text');
		$this->dbforge->drop_column('user_stats', 'screenshot');
		$this->dbforge->drop_column('user_stats', 'video');
		$this->dbforge->drop_column('user_stats', 'clip');
		mysql_query("ALTER TABLE  `user_stats`
										ADD  `images_count` INT UNSIGNED NOT NULL AFTER  `collections`,
										ADD  `texts_count` INT UNSIGNED NOT NULL AFTER  `collections`,
										ADD  `embeds_count` INT UNSIGNED NOT NULL AFTER  `collections`,
										ADD  `contents_count` INT UNSIGNED NOT NULL AFTER  `collections`,
										ADD  `htmls_count` INT UNSIGNED NOT NULL AFTER  `collections`
					");
		mysql_query("UPDATE newsfeed SET link_type_id = 1 WHERE link_type = 'html'");
		mysql_query("UPDATE newsfeed SET link_type_id = 2 WHERE link_type = 'content'");
		mysql_query("UPDATE newsfeed SET link_type_id = 3 WHERE link_type = 'embed'");
		mysql_query("UPDATE newsfeed SET link_type_id = 4 WHERE link_type = 'text'");
		mysql_query("UPDATE newsfeed SET link_type_id = 5 WHERE link_type = 'image'");
		
		mysql_query("UPDATE user_stats SET htmls_count = (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE link_type_id = 1 AND user_id_from = user_stats.user_id)");
		mysql_query("UPDATE user_stats SET contents_count = (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE link_type_id = 2 AND user_id_from = user_stats.user_id)");
		mysql_query("UPDATE user_stats SET embeds_count = (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE link_type_id = 3 AND user_id_from = user_stats.user_id)");
		mysql_query("UPDATE user_stats SET texts_count = (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE link_type_id = 4 AND user_id_from = user_stats.user_id)");
		mysql_query("UPDATE user_stats SET images_count = (SELECT COUNT(newsfeed_id) FROM newsfeed WHERE link_type_id = 5 AND user_id_from = user_stats.user_id)");
		
		mysql_query("ALTER TABLE `user_stats` CHANGE `user_id` `user_id` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `id`");
		mysql_query("ALTER TABLE `user_stats` CHANGE `redrop_got` `redrops_count` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `images_count`");
	}

	public function down()
	{
		
	}
}
