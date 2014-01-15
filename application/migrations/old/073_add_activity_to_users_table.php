<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_activity_to_users_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'fb_activity'=>array(
				'type'=>'ENUM',
				'constraint'=>"'1','0'",
			),
		));
		
		$this->dbforge->add_column('users', array(
			'twitter_activity'=>array(
				'type'=>'ENUM',
				'constraint'=>"'1','0'",
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('users', 'fb_activity');
		$this->dbforge->drop_column('users', 'twitter_activity');
		
	}
	
	
}
