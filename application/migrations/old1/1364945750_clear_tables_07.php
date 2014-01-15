<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_07 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('favorite_answer');
		$this->dbforge->drop_table('fb_links');
		$this->dbforge->drop_table('fb_newsfeeds');
		$this->dbforge->drop_table('fb_pages');
		$this->dbforge->drop_table('newsfeed_activity');
		$this->dbforge->drop_table('newsfeed_users');
		$this->dbforge->drop_table('privacy');
		$this->dbforge->drop_table('user_additional_info');
		$this->dbforge->drop_table('user_company');
		$this->dbforge->drop_table('user_links');
		$this->dbforge->drop_table('user_updated');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'q_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'order_no' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('q_id');
		$this->dbforge->create_table('favorite_answer');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'u_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'fb_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'twitter_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'link' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('u_id');
		$this->dbforge->add_key('fb_id');
		$this->dbforge->add_key('twitter_id');
		$this->dbforge->create_table('fb_links');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'fb_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'action' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('fb_id');
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->create_table('fb_newsfeeds');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'fb_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'fb_pageid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'fb_pagename' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'default' => ''
			),
			'fb_category' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('fb_id');
		$this->dbforge->add_key('fb_pageid');
		$this->dbforge->create_table('fb_pages');
		
		
		$this->dbforge->add_field(array(
			'aid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'folder_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'loop_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'activity_user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'activity_user_type' => array(
				'type' => 'ENUM',
				'constraint' => '"users","pages"'
			),
			'activity_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'reply_user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'reply_page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 32,
				'default' => ''
			),
			'a_data' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'user' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('aid', TRUE);
		$this->dbforge->create_table('newsfeed_activity');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('newsfeed_users');
		
		
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'level' => array(
				'type' => 'ENUM',
				'constraint' => '"open","closed"'
			)
		));
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('privacy');
		
		
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'type_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('type_id');
		$this->dbforge->create_table('user_additional_info');
		
		
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'company' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'default' => ''
			)
		));
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('user_company');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'label' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'url' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('user_links');
		
		
		$this->dbforge->add_field(array(
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'updated' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'default' => ''
			)
		));
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('user_updated');
	}
}
