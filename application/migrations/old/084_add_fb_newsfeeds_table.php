<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_fb_newsfeeds_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'fb_id' => array(
				'type' => 'BIGINT',
				'constraint' => '20',
			),
			'action' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => '11',
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('fb_id');
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->create_table('fb_newsfeeds');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('fb_newsfeeds');
	}
	
	
}
