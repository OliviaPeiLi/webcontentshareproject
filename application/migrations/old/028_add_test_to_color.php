<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_test_to_color extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('color', array(
			'test' => array(
				'type' => 'VARCHAR',
				'constraint' => '20',
				'default'=>1
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('color', 'test');
	}
}
