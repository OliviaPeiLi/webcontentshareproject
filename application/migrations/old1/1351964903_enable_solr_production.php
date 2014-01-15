<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_enable_solr_production extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE `modes_config` SET modes_config.production = 1, modes_config.staging = 1 WHERE name='solr_search'");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
