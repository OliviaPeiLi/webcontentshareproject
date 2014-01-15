<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_links_to_user_stats extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_column('user_stats', 'transfer_links');
		
		$this->dbforge->add_column('user_stats', array(
			'fb_links' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			)
		));
		
		$this->dbforge->add_column('user_stats', array(
			'twitter_links' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->add_column('user_stats', array(
			'transfer_links' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			)
		));
		$this->dbforge->drop_column('user_stats', 'fb_links');
		$this->dbforge->drop_column('user_stats', 'twitter_links');
	}
}
