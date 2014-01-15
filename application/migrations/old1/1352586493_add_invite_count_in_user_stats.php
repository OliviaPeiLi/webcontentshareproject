<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_invite_count_in_user_stats extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'invite_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('user_stats', 'invite_count');

	}
}
