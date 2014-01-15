<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_link_trimmed_left_top extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('links', array(
			'trimmed_left'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			),
			'trimmed_top'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('links', 'trimmed_left');
		$this->dbforge->drop_column('links', 'trimmed_top');
	}
	
	
}
