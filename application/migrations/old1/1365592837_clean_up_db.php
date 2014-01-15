<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_up_db extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('alpha_users', 'password');
		$this->dbforge->drop_column('alpha_users', 'fb_firstname');
		$this->dbforge->drop_column('alpha_users', 'fb_lastname');
		$this->dbforge->drop_column('alpha_users', 'fb_bday');
		$this->dbforge->drop_column('alpha_users', 'fb_gender');
		$this->dbforge->drop_column('alpha_users', 't_name');
		$this->dbforge->drop_column('alpha_users', 't_screen_name');
		$this->dbforge->drop_column('alpha_users', 'email_count');
		
		$this->dbforge->drop_column('comments', 'post_id');
		$this->dbforge->drop_column('comments', 'event_id');
		$this->dbforge->drop_column('comments', 'pr_id');
		
		$this->dbforge->drop_table('config');
		
		$this->dbforge->drop_column('connections', 'similarity');
		
		$this->dbforge->drop_table('gmail_invites');
		
		$this->dbforge->drop_column('likes', 'event_id');
		
		$this->dbforge->drop_column('links', 'thread_id');
		$this->dbforge->drop_column('links', 'img');
		$this->dbforge->drop_column('links', 'img_width');
		$this->dbforge->drop_column('links', 'img_height');
		$this->dbforge->drop_column('links', 'trimmed_left');
		$this->dbforge->drop_column('links', 'trimmed_top');
		
		$this->dbforge->drop_column('newsfeed', 'thread_id');
		$this->dbforge->drop_column('newsfeed', 'loop_id');
		$this->dbforge->drop_column('newsfeed', 'data');
		$this->dbforge->drop_column('newsfeed', 'user_id_to');
		$this->dbforge->drop_column('newsfeed', 'location');
		$this->dbforge->drop_column('newsfeed', 'latitude');
		$this->dbforge->drop_column('newsfeed', 'longitude');
		$this->dbforge->drop_column('newsfeed', 'city');
		$this->dbforge->drop_column('newsfeed', 'province');
		$this->dbforge->drop_column('newsfeed', 'country');
		
		$this->dbforge->drop_table('twitter_follow');
	}

	public function down()
	{
		
	}
}
