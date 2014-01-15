<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_admin_requests extends CI_Migration {

	public function up()
	{
		
		
		mysql_query("
			CREATE TABLE IF NOT EXISTS `user_admin_request` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `status` set('pending','approved','denied') DEFAULT 'pending',
			  `updated` timestamp NULL DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		");
		
	}

	public function down()
	{
		
		  
		  mysql_query("DROP TABLE user_admin_request");
		
	}
}
