<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_orig_user_index extends CI_Migration {

	public function up()
	{		
		mysql_query("ALTER TABLE  `newsfeed` ADD INDEX (  `orig_user_id` )");
	}

	public function down()
	{
		mysql_query("ALTER TABLE newsfeed DROP INDEX orig_user_id");
	}
}
