<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_promoters extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `promoters` (
						  `id` int(11) NOT NULL,
						  `email` varchar(255) NOT NULL,
						  `created_at` datetime NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		mysql_query("CREATE TABLE IF NOT EXISTS `publishers` (
						  `id` int(11) NOT NULL,
						  `url` varchar(255) NOT NULL,
						  `created_at` datetime NOT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	}

	public function down() {
		
	}
}
