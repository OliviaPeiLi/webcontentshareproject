<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_share_count_in_newsfeed extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `twitter_share_count` INT(11) NOT NULL DEFAULT 0 AFTER  `fb_share_count`");
		mysql_query("ALTER TABLE  `newsfeed` ADD  `pinterest_share_count` INT(11) NOT NULL DEFAULT 0 AFTER  `fb_share_count`");
	}

	public function down()
	{
		mysql_query("ALTER TABLE `newsfeed` DROP `twitter_share_count`");
		mysql_query("ALTER TABLE `newsfeed` DROP `pinterest_share_count`");
	}
}
