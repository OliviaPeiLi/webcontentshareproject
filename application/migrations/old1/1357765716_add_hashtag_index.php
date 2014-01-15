<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_hashtag_index extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `newsfeed` ADD INDEX (  `hashtag_id` )");
	}

	public function down()
	{
		mysql_query("ALTER TABLE newsfeed DROP INDEX hashtag_id");
	}
}
