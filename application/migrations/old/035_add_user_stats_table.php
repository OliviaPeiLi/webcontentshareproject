<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_user_stats_table extends CI_Migration {


	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'collections' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'drops' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'likes' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('user_stats');
	}
	
	
	public function down()
	{
        $this->dbforge->drop_table('user_stats');
	}
	
}
