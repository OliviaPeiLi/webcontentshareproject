<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_link_type_id_in_newsfeed extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `link_type_id` TINYINT( 1 ) NOT NULL COMMENT  '1-html,2-content,3-embed,4-text,5-image' AFTER  `link_type` ,
						ADD INDEX (  `link_type_id` )");
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'link_type_id');
	}
}
