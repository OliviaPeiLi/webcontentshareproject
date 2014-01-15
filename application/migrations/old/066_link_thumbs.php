<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Link_thumbs extends CI_Migration {

	public function up()
	{		
		//img column is actualy source image so renaming to avoid confusion and max URL length in IE is 2048 so we are supposing thats the max accepted length at all
		mysql_query("ALTER TABLE  `links` CHANGE  `img`  `source_img` VARCHAR( 2048 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		//setting s3_img to img to be used by uploadable behavior
		mysql_query("ALTER TABLE  `links` CHANGE  `s3_img`  `img` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		//uploadable behavior doesnt need additional fields for the thumbnails
		mysql_query("ALTER TABLE `links` DROP `s3_thumb`");
		//optimization fix - duplicated index
		mysql_query("ALTER TABLE `links` DROP INDEX `page_id`");
	}

	public function down()
	{
		mysql_query("ALTER TABLE  `links` CHANGE  `img`  `s3_img` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		mysql_query("ALTER TABLE  `links` CHANGE  `source_img`  `img` VARCHAR( 8000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		mysql_query("ALTER TABLE  `links` ADD  `s3_thumb` VARCHAR( 300 ) NOT NULL AFTER  `s3_img`");
	}
}
