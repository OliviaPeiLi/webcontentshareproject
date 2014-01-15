<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_user_stats extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('user_stats', array(
			'comment_got'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		$this->dbforge->add_column('user_stats', array(
			'up_got'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		$this->dbforge->add_column('user_stats', array(
			'redrop_got'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
	}

	public function down()
	{
		
		$this->dbforge->drop_column('user_stats', 'comment_got');
		$this->dbforge->drop_column('user_stats', 'up_got');
		$this->dbforge->drop_column('user_stats', 'redrop_got');
	}
}
