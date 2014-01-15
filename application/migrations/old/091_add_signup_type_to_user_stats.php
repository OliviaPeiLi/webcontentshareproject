<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_signup_type_to_user_stats extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'signup_type'=>array(
				'type' => 'VARCHAR',
				'constraint' => '32',
				'default' => ''
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'signup_type');
	}
	
	
}
