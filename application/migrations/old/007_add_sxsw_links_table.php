<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_sxsw_links_table extends CI_Migration {

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
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => '1000',
			),
			'image' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_links');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('sxsw_links');
	}
	
	
}
