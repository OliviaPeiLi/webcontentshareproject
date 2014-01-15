<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsfeed_share_table extends CI_Migration {

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
				'constraint' => 11,
				'default' => 0,
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
			'created_at' => array(
				'type' => 'TIMESTAMP'
			),
			'api' => array(
				'type' => 'ENUM',
				'constraint' => "'fb','twitter','pinterest'"
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->create_table('newsfeed_shares');
	}

	public function down()
	{
		$this->dbforge->drop_table('newsfeed_shares');
	}
}
