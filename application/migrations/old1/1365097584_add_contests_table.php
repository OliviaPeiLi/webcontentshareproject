<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_contests_table extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `contests` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `user_id` int(10) unsigned NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `url` varchar(255) NOT NULL,
					  `logo` varchar(255) NOT NULL,
					  `created_at` datetime NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `user_id` (`user_id`,`url`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
				");
	}

	public function down()
	{
		  $this->dbforge->drop_table('contests');
	}
}
