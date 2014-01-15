<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_updated_at_to_folder extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `folder` ADD  `updated_at` DATETIME NOT NULL AFTER  `ranked_at` ,
					ADD INDEX (  `updated_at` )");
		mysql_query("ALTER TABLE  `users` ADD  `fb_token` VARCHAR(255) NOT NULL AFTER  `fb_id`");
	}

	public function down()
	{
		$this->dbforge->drop_column('folder', 'updated_at');
	}
}
