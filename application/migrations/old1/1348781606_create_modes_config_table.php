<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_modes_config_table extends CI_Migration {

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
				'constraint' => '255',
				'default' => '',
			),
			'development' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => 0,
			),
			'staging' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => 0,
			),
			'production' => array(
				'type' => 'TINYINT',
				'constraint' => '1',
				'default' => 0,
			),
			'custom' => array(
				'type' => 'TEXT',
				'default' => '',
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('modes_config');
	}

	public function down()
	{
		$this->dbforge->drop_table('modes_config');
	}
}
