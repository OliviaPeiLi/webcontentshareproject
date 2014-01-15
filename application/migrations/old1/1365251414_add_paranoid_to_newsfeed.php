<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_paranoid_to_newsfeed extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `is_deleted` TINYINT UNSIGNED NOT NULL AFTER  `source_id` ,
					ADD INDEX (  `is_deleted` )");
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'is_deleted');
	}
}
