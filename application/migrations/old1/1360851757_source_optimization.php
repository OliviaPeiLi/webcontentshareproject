<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Source_optimization extends CI_Migration {

	public function up()
	{
		//Hope the server will handle this...
		mysql_query("ALTER TABLE  `newsfeed` ADD  `source_id` INT UNSIGNED NOT NULL,
					ADD INDEX (  `source_id` )");
		mysql_query("CREATE TABLE IF NOT EXISTS `sources` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `source` varchar(255) NOT NULL,
						  PRIMARY KEY (`id`),
						  KEY `source` (`source`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		mysql_query("INSERT INTO sources (source) SELECT DISTINCT REPLACE( REPLACE(
						SUBSTRING(link_url from 1 for locate('/',link_url ,10)-1),'www.',''),'http://','') 
					FROM newsfeed"); 
		mysql_query("DELETE FROM sources WHERE source = ''");
		mysql_query("UPDATE newsfeed SET source_id = (SELECT id FROM sources WHERE source = REPLACE( REPLACE(
						SUBSTRING(link_url from 1 for locate('/',link_url ,10)-1),'www.',''),'http://',''))");
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'source_id');
		$this->dbforge->drop_table('sources');
	}
}
