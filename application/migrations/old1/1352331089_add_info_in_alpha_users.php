<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_info_in_alpha_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'info'=>array(
				'type'=>'VARCHAR',
				'constraint'=>50,
				'default'=>''
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('users', 'info');
		
	}
}
