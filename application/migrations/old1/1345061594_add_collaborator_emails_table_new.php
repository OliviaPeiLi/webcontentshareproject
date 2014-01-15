<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_collaborator_emails_table_new extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'folder_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			),
			'c_code' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('folder_id');
		$this->dbforge->create_table('collaborator_emails');
	}

	public function down()
	{
		$this->dbforge->drop_table('collaborator_emails');
	}
}
