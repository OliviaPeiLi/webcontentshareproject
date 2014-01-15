<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Undefined_var extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'view_page' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
			),
			'variable' => array(
				'type' => 'VARCHAR',
				'constraint' => '511',
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('undefined_var');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('undefined_var');
	}
}
