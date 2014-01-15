<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_badges_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			),
			'uri' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			),
			'img' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			),
			'description'=>array(
				'type' => 'TEXT',
				'default' => '',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('badges');
	}

	public function down()
	{
		$this->dbforge->drop_table('badges');
	}
}
