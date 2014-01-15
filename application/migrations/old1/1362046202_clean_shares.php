<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_shares extends CI_Migration {

	public function up() {
		mysql_query("ALTER TABLE  `newsfeed_shares` ADD  `folder_id` INT(11) NOT NULL DEFAULT 0 AFTER  `newsfeed_id`,
						ADD INDEX ( `api` )");
		mysql_query("INSERT INTO `newsfeed_shares` 
						SELECT NULL, users.id, newsfeed_id, folder_id, `time`, 'fb' FROM fb_drops
						JOIN users ON (fb_drops.fb_id = users.fb_id)
				");
		mysql_query("DROP TABLE `fb_drops`");
		mysql_query("UPDATE newsfeed SET fb_share_count = (SELECT COUNT(id) FROM newsfeed_shares WHERE newsfeed.newsfeed_id = newsfeed_shares.newsfeed_id)");		
	}

	public function down() {
		
	}
}
