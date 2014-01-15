<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_fix_news_rank extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `newsfeed` ADD  `is_ranked` TINYINT UNSIGNED NOT NULL AFTER  `news_rank` ,
					ADD  `ranked_at` DATETIME NOT NULL AFTER  `is_ranked` ,
					ADD INDEX (  `is_ranked` )");
	}

	public function down()
	{
		mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		mysql_query("ALTER TABLE `newsfeed` DROP `ranked_at`");
	}
}
