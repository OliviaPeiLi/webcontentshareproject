<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_catmemes_contest_page extends CI_Migration {

	public function up()
	{
		$user = mysql_fetch_object(mysql_query("SELECT id FROM users WHERE `uri_name` = 'alexi'"));
		mysql_query("INSERT INTO folder (`folder_name`, `type`, `user_id`, `folder_uri_name`, `sort_by`)
					VALUES ('kittenmeme', 2, $user->id, 'kittenmeme', 2)");
	}

	public function down()
	{
		
	}
}
