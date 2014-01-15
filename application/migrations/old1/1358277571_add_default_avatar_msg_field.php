<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_default_avatar_msg_field extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'default_avatar_msg'=>array(
				'type'		 => 'TINYINT',
				'constraint' => 1,
				'default'	 => 0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'default_avatar_msg');
	}
}
