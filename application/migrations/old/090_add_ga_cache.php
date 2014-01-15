<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_ga_cache extends CI_Migration {

	public function up()
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS `ga_cache` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `func` varchar(255) NOT NULL,
		  `param` varchar(255) NOT NULL,
		  `val` int(10) unsigned NOT NULL,
		  `updated_at` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `func` (`func`,`param`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
	}

	public function down()
	{
		$this->db->query("DROP TABLE ga_cache");
	}
}
