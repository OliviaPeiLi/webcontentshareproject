<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_edit_alpha_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('alpha_users', array(
			'user_id'=>array(
				'type'=>'INT',
				'constraint'=>'11',
				'default'=>'0'
			),
		));
		
		$this->dbforge->add_column('alpha_users', array(
			'message'=>array(
				'type'=>'TEXT',
				'default'=>''
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('alpha_users', 'user_id');
		$this->dbforge->drop_column('alpha_users', 'message');
		
	}
	
	
}
