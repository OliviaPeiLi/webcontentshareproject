<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newsfeed_referals extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `newsfeed_referrals` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `newsfeed_id` int(11) NOT NULL,
					  `name` VARCHAR(255) NOT NULL,
					  `email` VARCHAR(255) NOT NULL,
					  `points` int(11) NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `newsfeed_id` (`newsfeed_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	public function down()
	{
		
	}
}
