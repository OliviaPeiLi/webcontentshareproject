<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('newsfeed', 'comments_cache');
		$this->dbforge->drop_column('newsfeed', 'likes_cache');
		$this->dbforge->drop_column('newsfeed', 'user_drops');
		$this->dbforge->drop_column('newsfeed', 'source_drops');
	}

	public function down()
	{
		/*
		  $this->dbforge->drop_column('users', 'role');
		  mysql_query("ALTER TABLE `newsfeed` DROP `is_ranked`");
		*/
	}
}
