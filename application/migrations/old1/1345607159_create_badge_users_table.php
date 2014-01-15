<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_badge_users_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'badge_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('badge_id');
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('badge_users');
	}

	public function down()
	{
		$this->dbforge->drop_table('badge_users');
	}
}
