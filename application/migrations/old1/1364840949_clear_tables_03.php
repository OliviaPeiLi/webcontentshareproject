<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_03 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('page_merge');
		$this->dbforge->drop_table('page_official_requests');
		$this->dbforge->drop_table('page_thread');
		$this->dbforge->drop_table('page_topics');
		$this->dbforge->drop_table('page_users');
		$this->dbforge->drop_table('page_user_relation');
		$this->dbforge->drop_table('photo_tags');
		$this->dbforge->drop_table('points_system');
		$this->dbforge->drop_table('posts');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'm_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
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
			)
		));
		$this->dbforge->add_key('m_id', TRUE);
		$this->dbforge->create_table('page_merge');
		
		
		$this->dbforge->add_field(array(
			'r_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
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
			'new_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('m_id', TRUE);
		$this->dbforge->create_table('page_official_requests');
		
		
		$this->dbforge->add_field(array(
			't_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
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
			'thread_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'views' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'replies' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			),
		));
		$this->dbforge->add_key('t_id', TRUE);
		$this->dbforge->create_table('page_thread');
		
		
		$this->dbforge->add_field(array(
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
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
			'topic_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('topic_id', TRUE);
		$this->dbforge->create_table('page_topics');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
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
			'role' => array(
				'type' => 'ENUM',
				'constraint' => "'OWNER','ADMIN','FAN','EDITOR','MOD'"
			),
			'vibe' => array(
				'type' => 'TINYINT',
				'constraint' => 3,
				'default' => 0
			),
			'posts' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'photos' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'links' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'comments' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'ups' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('page_id');
		$this->dbforge->create_table('page_users');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user1_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user2_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'description' => array(
				'type' => 'TEXT',
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user1_id');
		$this->dbforge->add_key('user2_id');
		$this->dbforge->add_key('page_id');
		$this->dbforge->create_table('page_user_relation');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'photo_id' => array(
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
			'time' => array(
				'type' => 'VARCHAR',
				'constraint' => 32,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('photo_tags');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
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
			'points' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'like_points' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('points_system');
		
		
		$this->dbforge->add_field(array(
			'post_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'thread_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id_from' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id_to' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id_from' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id_to' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'post' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP',
				'default' => ''
			)
		));
		$this->dbforge->add_key('post_id', TRUE);
		$this->dbforge->create_table('posts');
	}
}
