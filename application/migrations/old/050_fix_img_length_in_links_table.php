<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_fix_img_length_in_links_table extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `links` CHANGE  `img`  `img` VARCHAR( 8000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `links` CHANGE  `img`  `img` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
	}
}
