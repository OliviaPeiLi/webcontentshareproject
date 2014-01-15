<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_bookmarklet_msg_in_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'bookmarklet_msg'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>1
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('users', 'bookmarklet_msg');
	}
}
