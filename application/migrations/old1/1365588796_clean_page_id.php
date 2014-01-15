<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_page_id extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('comments', 'page_id_from');
		$this->dbforge->drop_column('comments', 'page_id_to');
		$this->dbforge->drop_column('comments', 'reply_user_id');
		$this->dbforge->drop_column('comments', 'reply_page_id');
		
		$this->dbforge->drop_column('activities', 'page_id_from');
		$this->dbforge->drop_column('activities', 'page_id_to');
		
		$this->dbforge->drop_column('likes', 'page_id');
		$this->dbforge->drop_column('likes', 'post_id');
		$this->dbforge->drop_column('likes', 'pr_id');
				
		$this->dbforge->drop_column('links', 'page_id_from');
		$this->dbforge->drop_column('links', 'page_id_to');
		
		$this->dbforge->drop_column('photos', 'page_id_from');
		$this->dbforge->drop_column('photos', 'page_id_to');
		
		$this->dbforge->drop_column('newsfeed', 'page_id_from');
		$this->dbforge->drop_column('newsfeed', 'page_id_to');
		$this->dbforge->drop_column('newsfeed', 'page_type');
		
		$this->dbforge->drop_column('notifications', 'page_id_from');
		$this->dbforge->drop_column('notifications', 'page_id_to');
	}

	public function down()
	{
		
	}
}
