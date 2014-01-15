<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Admin_logins extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `admin_logins` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ip` varchar(16) NOT NULL,
			  `agent` varchar(255) NOT NULL,
			  `num` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `ip` (`ip`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;");
	}

	public function down()
	{
		
	}
}
