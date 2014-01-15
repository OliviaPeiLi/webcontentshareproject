<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_email_count extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('alpha_users', array(
			'email_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			),

		));
	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('alpha_users', 'email_count');
	}
	
	
}
