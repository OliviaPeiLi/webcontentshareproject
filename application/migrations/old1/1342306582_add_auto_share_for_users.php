<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_auto_share_for_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('users', array(
			'auto_share'=>array(
				'type' => 'ENUM',
				'constraint' => "'1','0'"
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'auto_share');
	}
}
