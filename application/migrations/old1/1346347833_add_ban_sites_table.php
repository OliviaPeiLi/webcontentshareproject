<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_ban_sites_table extends CI_Migration {

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
			'url' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ban_sites');
	}

	public function down()
	{
		$this->dbforge->drop_table('ban_sites');
	}
}
