<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_contest extends CI_Migration {

	public function up()
	{
		$kitten = mysql_fetch_object(mysql_query("SELECT * FROM folder WHERE folder_uri_name = 'kittencamp'"));
		
		mysql_query("ALTER TABLE  `contests` ADD  `is_simple` TINYINT (1) NOT NULL AFTER  `logo`");
		
		mysql_query("INSERT INTO contests (`user_id`, `name`, `url`, `logo`, `is_simple`, `created_at`)
						VALUES ($kitten->user_id, 'kittencamp', 'kittencamp', 'kittencamp.png', 1, NOW())");
		$contest_id = mysql_insert_id();
		
		mysql_query("UPDATE folder SET user_id = $contest_id, is_open  = 1 WHERE folder_id = $kitten->folder_id");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
