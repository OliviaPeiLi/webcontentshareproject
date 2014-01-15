<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_verified_in_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'verified'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));

	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'verified');
	}
}
