<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newsfeed_optimization extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` CHANGE  `complete`  `complete` TINYINT( 1 ) NOT NULL");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `newsfeed` CHANGE  `complete`  `complete` ENUM(  '0',  '1' ) NOT NULL");
	}
}
