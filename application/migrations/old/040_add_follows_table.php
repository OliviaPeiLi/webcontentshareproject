<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_follows_table extends CI_Migration {


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
			'follow_mode' => array(
				'type' => 'VARCHAR',
				'constraint' => '20',
				'default' => '',
			),
			'following_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('follow_id');
		$this->dbforge->create_table('follows');
	}
	
	
	public function down()
	{
        $this->dbforge->drop_table('follows');
	}
	
}
