<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsfeed_hushtags_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0
			),
			'hashtag_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->add_key('hashtag_id');
		$this->dbforge->create_table('newsfeed_hashtags');
	}

	public function down()
	{
		$this->dbforge->drop_table('newsfeed_hashtags');
	}
}
