<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user_education extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'year' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => '',
			),
			'school'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			),
			'degree'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			),
			'concentration'=>array(
				'type' => 'text',
				'default' => '',
			),
			'classes'=>array(
				'type' => 'text',
				'default' => '',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('user_education');
	}

	public function down()
	{
		$this->dbforge->drop_table('user_education');
	}
}
