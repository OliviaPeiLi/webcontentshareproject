<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_scripts_table extends CI_Migration {

	public function up()
	{
		mysql_query("CREATE TABLE IF NOT EXISTS `scripts` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `script` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
					  `instances` int(11) NOT NULL,
					  `description` text NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		mysql_query("INSERT INTO scripts (script, instances, description) VALUES ('clean_html', 1, 'Cleans the html of bookmarked pages and downloads the static files')");
	}

	public function down()
	{
		mysql_query("DROP TABLE `scripts`");
	}
}
