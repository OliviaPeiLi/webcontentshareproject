<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_social_user_id_in_shares extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed_shares` ADD  `social_user_id` INT UNSIGNED NOT NULL ,
					ADD INDEX ( `social_user_id` )");
	}

	public function down()
	{
	}
}
