<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_sxsw_users_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'fb_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'twitter_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
			'first_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'last_name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '200',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_users');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('sxsw_users');
	}
	
	
}
