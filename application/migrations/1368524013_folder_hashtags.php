<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Folder_hashtags extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `folder_hashtags` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `folder_id` int(11) NOT NULL,
					  `hashtag_id` int(11) NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `folder_id` (`folder_id`),
					  KEY `hashtag_id` (`hashtag_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		
		mysql_query("INSERT INTO folder_hashtags (folder_id, hashtag_id) SELECT folder_id, hashtag_id FROM folder");
	}

	public function down()
	{
		mysql_query("DROP TABLE `folder_hashtags`");
	}
}
