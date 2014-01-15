<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_scripts_table extends CI_Migration {

	public function up()
	{
		mysql_query("DROP TABLE IF EXISTS `scripts`;");
		mysql_query("CREATE TABLE `scripts` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
						  `num_instances` int(11) NOT NULL,
						  `description` text NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;");
		if (ENVIRONMENT == 'staging') {
			mysql_query("INSERT INTO `scripts` (`script`, `instances`, `description`) VALUES
						('clean_html', 1, 'Cleans the html of bookmarked pages and downloads the static files');");
		} elseif (ENVIRONMENT == 'production') {
			mysql_query("INSERT INTO `scripts` (`script`, `instances`, `description`) VALUES
						('newsfeed_ranking.php', 1, 'Calculates the newsfeed rank field');");
			mysql_query("INSERT INTO `scripts` (`script`, `instances`, `description`) VALUES
						('collection_ranking.php', 1, 'Calculates the folder rank field');");
		}
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
