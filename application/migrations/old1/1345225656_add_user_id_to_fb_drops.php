<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user_id_to_fb_drops extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `fb_drops` ADD  `user_id` INT UNSIGNED NOT NULL AFTER  `id` ,
					ADD INDEX (  `user_id` )");
		mysql_query("UPDATE fb_drops SET user_id = (SELECT id FROM users WHERE fb_drops.fb_id = users.fb_id LIMIT 1)");
	}

	public function down()
	{
		$this->dbforge->drop_column('fb_drops', 'user_id');
	}
}
