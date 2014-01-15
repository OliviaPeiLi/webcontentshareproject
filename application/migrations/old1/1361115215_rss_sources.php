<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Rss_sources extends CI_Migration {

	public function up()
	{
		mysql_query("INSERT INTO `modes_config` (`name`, `development`, `staging`, `production`, `description`) 
				VALUES ('rss_auto_port', 1, 1, 0, 'Automaticaly updates folders ported to rss or some api')");
		mysql_query("CREATE TABLE IF NOT EXISTS `rss_sources` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `source` varchar(255) NOT NULL,
						  `update_on` tinyint NOT NULL COMMENT '-1 - never (API); 0 - every 30mins; 1 - every hour; 6- every 6 hours; 12 - every 12 hours ... etc.',
						  `updated_at` DATETIME NOT NULL,
						  PRIMARY KEY (`id`),
						  KEY `source` (`source`),
						  KEY `updated_at` (`updated_at`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;"); //First 100 are saved for APIs
		mysql_query("INSERT INTO  `rss_sources` (`id`, `source` ,`update_on` ,`updated_at`)
						VALUES (1, 'facebook.com',  -1,  NOW()), (2, 'twitter.com',  -1,  NOW());");
		
		mysql_query("ALTER TABLE  `folder` ADD  `rss_source_id` INT NOT NULL COMMENT  'First 100 are saved for APIs' AFTER  `user_id` ,
						ADD INDEX (  `rss_source_id` )");
	}

	public function down()
	{
		mysql_query("DELETE FROM `modes_config` WHERE name = 'rss_auto_port'");
		$this->dbforge->drop_column('folder', 'rss_source_id');
		$this->dbforge->drop_table('rss_sources');
	}
}
