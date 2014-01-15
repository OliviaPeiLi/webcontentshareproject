<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_role_to_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'role'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'role');
	}
}
