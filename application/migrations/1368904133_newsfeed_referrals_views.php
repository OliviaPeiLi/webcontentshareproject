<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newsfeed_referrals_views extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `newsfeed_referrals_views` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `newsfeed_referrals_id` int(11),
					  `ip` int(11) NOT NULL,
					  `views` int(11) NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `newsfeed_referrals_id` (`newsfeed_referrals_id`),
					  UNIQUE KEY `ip` (`newsfeed_referrals_id`, `ip`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
