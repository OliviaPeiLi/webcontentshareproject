<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_up_count_in_comments extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('comments', array(
			'up_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
	}

	public function down()
	{
		
		$this->dbforge->drop_column('comments', 'up_count');
		
	}
}
