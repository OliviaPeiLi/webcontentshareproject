<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clean_b extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('users', 'current_city');
		$this->dbforge->drop_column('users', 'email_activated');
		$this->dbforge->drop_column('users', 'followable');
		$this->dbforge->drop_column('users', 'connections');
		$this->dbforge->drop_column('users', 'interests');
		$this->dbforge->drop_column('users', 'key');
		$this->dbforge->drop_column('users', 'num_topics');
		$this->dbforge->drop_column('users', 'avatar_width');
		$this->dbforge->drop_column('users', 'avatar_height');
		$this->dbforge->drop_column('users', 'bookmarklet_settings');
		$this->dbforge->drop_column('users', 'auto_share');
		$this->dbforge->drop_column('users', 'bookmarklet_msg');
		
		$this->dbforge->drop_table('user_admin_request');
		
		$this->dbforge->drop_column('user_stats', 'signup_type');
	}

	public function down()
	{

	}
}
