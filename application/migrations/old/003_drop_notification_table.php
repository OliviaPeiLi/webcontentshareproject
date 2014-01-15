<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_drop_notification_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('notification');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'notification_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'wall' => array(
				'type' => 'VARCHAR',
				'constraint' => '11',
			),
			'poster' => array(
				'type' => 'VARCHAR',
				'constraint' => '11',
			),
			'reply' => array(
				'type' => 'VARCHAR',
				'constraint' => '11',
			),
			'read_wall' => array(
				'type' => 'ENUM',
				'constraint' => "'0','1','2'",
			),
			'read_poster' => array(
				'type' => 'ENUM',
				'constraint' => "'0','1','2'",
			),
			'read_reply' => array(
				'type' => 'ENUM',
				'constraint' => "'0','1','2'",
			),
		));
		$this->dbforge->add_key('notification_id', TRUE);
		$this->dbforge->create_table('notification');
	}
}
