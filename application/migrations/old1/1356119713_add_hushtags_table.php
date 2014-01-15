<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_hushtags_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'hashtag' => array(
				'type' => 'VARCHAR',
				'constraint' => '250',
				'default' => ''
			),
			'count' => array(
				'type' => 'TEXT',
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('hashtag');
		$this->dbforge->create_table('hashtags');
	}

	public function down()
	{
		$this->dbforge->drop_table('hashtags');
	}
}
