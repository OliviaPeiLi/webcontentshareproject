<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_index_to_links extends CI_Migration {

	public function up()
	{
		/*
		mysql_query("ALTER TABLE  `links` CHANGE  `link`  `link` VARCHAR( 2048 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		mysql_query("ALTER TABLE  `fantoon_ci`.`links` ADD INDEX (`link`)");
		*/
	}

	public function down()
	{
		//mysql_query("`ALTER TABLE links DROP INDEX link`");
	}
}
