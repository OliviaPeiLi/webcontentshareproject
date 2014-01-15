<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Fix_photos_img_fields extends CI_Migration {

	public function up()
	{
		  $this->dbforge->drop_column('photos', 'thumbnail');
		  mysql_query("ALTER TABLE  `photos` CHANGE  `full_img`  `img` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0'");
		  mysql_query("ALTER TABLE  `photos` CHANGE  `full_img_width`  `img_width` INT( 11 ) NOT NULL DEFAULT  '0',
						CHANGE  `full_img_height`  `img_height` INT( 11 ) NOT NULL DEFAULT  '0'");
	}

	public function down()
	{
		  mysql_query("ALTER TABLE  `photos` CHANGE  `img_width`  `full_img_width` INT( 11 ) NOT NULL DEFAULT  '0',
						CHANGE  `img_height`  `full_img_height` INT( 11 ) NOT NULL DEFAULT  '0'");
		mysql_query("ALTER TABLE  `photos` ADD  `thumbnail` VARCHAR( 255 ) NOT NULL AFTER  `img`");
		mysql_query("ALTER TABLE  `photos` CHANGE `img` `full_img` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '0'");
	}
}
