<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_new_olympics_img_in_topics extends CI_Migration {

	public function up()
	{
		mysql_query("UPDATE topics SET img = 'olympics.png' WHERE topic_name='Olympics'");
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
