<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_three_fields_in_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'friend_msg'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
		
		$this->dbforge->add_column('users', array(
			'password_msg'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
		
		$this->dbforge->add_column('users', array(
			'friends_array'=>array(
				'type'=>'text',
				'default'=>''
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('users', 'friend_msg');
		$this->dbforge->drop_column('users', 'password_msg');
		$this->dbforge->drop_column('users', 'friends_array');
	}
}
