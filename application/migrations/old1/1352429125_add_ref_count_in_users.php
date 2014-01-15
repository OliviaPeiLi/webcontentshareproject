<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_ref_count_in_users extends CI_Migration {
//actually in user_stats table.
	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'ref_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('user_stats', 'ref_count');
		
	}
}
