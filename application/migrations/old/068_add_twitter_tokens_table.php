<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_twitter_tokens_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'twitter_id' => array(
				'type' => 'BIGINT',
				'constraint' => '20',
			),
			'token' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'token_secret' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('twitter_id');
		$this->dbforge->create_table('twitter_tokens');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('twitter_tokens');
	}
	
	
}
