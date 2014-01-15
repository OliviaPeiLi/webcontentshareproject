<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_email_from_alpha_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('alpha_users', 'email');
	}

	public function down()
	{
		$this->dbforge->add_column('alpha_users', array(
			'email'=>array(
				'type'=>'VARCHAR',
				'constraint'=>50,
				'default'=>''
			)
		));
	}
}
