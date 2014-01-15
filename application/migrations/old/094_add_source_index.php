<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_source_index extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `links` CHANGE  `source`  `source` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		mysql_query("ALTER TABLE  `links` ADD INDEX (  `source` )");
	}

	public function down()
	{
		mysql_query("ALTER TABLE links DROP INDEX source");
		mysql_query("ALTER TABLE  `links` CHANGE  `source`  `source` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}
}
