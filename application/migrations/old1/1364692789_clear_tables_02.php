<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_02 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('pages');
		$this->dbforge->drop_table('pages_similarity');
		$this->dbforge->drop_table('page_aliases_request');
		$this->dbforge->drop_table('page_category');
		$this->dbforge->drop_table('page_feature');
		$this->dbforge->drop_table('page_info');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'owner_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'avatar' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'thumbnail' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'page_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'uri_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'official_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'fb_pageid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'twitter_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'category_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'interest_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'alias_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'alias_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'redirect_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'topic_lock' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			),
			'pr_lock' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			),
			'hits' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'sign_up_date' => array(
				'type' => 'TIMESTAMP'
			),
			'score' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'wiki_time' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'avatar_width' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'avatar_height' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('page_id', true);
		$this->dbforge->create_table('pages');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'page1_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page2_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'similarity' => array(
				'type' => 'FLOAT',
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('page1_id');
		$this->dbforge->add_key('page2_id');
		$this->dbforge->create_table('pages_similarity');
		
		
		$this->dbforge->add_field(array(
			'r_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'alias' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'hits' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('r_id', true);
		$this->dbforge->add_key('page_id');
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('page_aliases_request');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'form' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('page_category');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'page1_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page2_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'type' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('page1_id', true);
		$this->dbforge->add_key('page2_id', true);
		$this->dbforge->create_table('page_feature');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'address' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'city' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'zip' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'phone' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'founded' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'about' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'description' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'website' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'written_by' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'hour' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'serve' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'specialty' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'food' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'product' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'award' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'interest' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'gender' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'birthday' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'hometown' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'college' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'biograph' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'current_location' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'education' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'manager_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'booking_agent' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'press_contact' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'influence' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'release_date' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'record_label' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'member' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'schedule' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'isbn' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'genre' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'directed_by' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'starring' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('page_id');
		$this->dbforge->create_table('page_info');
	}
}
