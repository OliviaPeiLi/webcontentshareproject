<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsletters_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'subject' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => 'Fandrop Newsletter',
			),
			'msg' => array(
				'type' => 'TEXT',
				'default' => '',
			),
			'time' => array(
				'type' => 'INT',
				'constraint' => '20',
				'default' => 0,
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('time');
		$this->dbforge->create_table('newsletters');
	}

	public function down()
	{
		$this->dbforge->drop_table('newsletters');
	}
}
