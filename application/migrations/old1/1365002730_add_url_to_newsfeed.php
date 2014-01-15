<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_url_to_newsfeed extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD  `url` VARCHAR (255) NOT NULL AFTER  `data` ,
					ADD INDEX (  `url` )");
	}

	public function down()
	{
		  $this->dbforge->drop_column('newsfeed', 'url');
	}
}
