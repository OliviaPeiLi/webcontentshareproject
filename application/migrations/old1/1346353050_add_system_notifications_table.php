<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_system_notifications_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => ''
			),
			'content' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('system_notifications');
		
		$this->dbforge->add_column('users', array(
			'notification_time'=>array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_table('system_notifications');
		$this->dbforge->drop_column('users', 'notification_time');
	}
}
