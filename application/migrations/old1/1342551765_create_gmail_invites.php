<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_gmail_invites extends CI_Migration {

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
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('gmail_invites');
	}

	public function down()
	{
		$this->dbforge->drop_table('gmail_invites');
	}
}
