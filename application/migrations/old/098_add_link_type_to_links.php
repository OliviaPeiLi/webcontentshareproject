<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_link_type_to_links extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `links` ADD  `link_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `page_id_to` ,
					ADD INDEX (  `link_type` )");
		mysql_query("UPDATE links SET link_type = (SELECT link_type FROM newsfeed WHERE activity_id = link_id AND type = 'link')");
	}

	public function down()
	{
		mysql_query("ALTER TABLE links DROP link_type");
	}
}
