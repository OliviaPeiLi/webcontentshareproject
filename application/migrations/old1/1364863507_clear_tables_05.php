<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_05 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('tab_content');
		$this->dbforge->drop_table('test');
		$this->dbforge->drop_table('test_table_users');
		$this->dbforge->drop_table('topic_aliases');
		$this->dbforge->drop_table('topic_children');
		$this->dbforge->drop_table('topic_event');
		$this->dbforge->drop_table('topic_folders');
		$this->dbforge->drop_table('topic_link');
		$this->dbforge->drop_table('topic_merge');
		$this->dbforge->drop_table('topic_newsfeed');
		$this->dbforge->drop_table('topic_page');
		$this->dbforge->drop_table('topic_photo');
		$this->dbforge->drop_table('topic_points');
		$this->dbforge->drop_table('topic_post');
		$this->dbforge->drop_table('topic_pr');
		$this->dbforge->drop_table('topic_relationship');
		$this->dbforge->drop_table('topic_tracker');
		$this->dbforge->drop_table('topic_user');
		$this->dbforge->drop_table('topic_user_follow');
		$this->dbforge->drop_table('usersimilarity');
		$this->dbforge->drop_table('users_similarity');
		$this->dbforge->drop_table('user_percentile');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'component_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'orderid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'tab_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'type' => array(
				'type' => 'ENUM',
				'constraint' => "'text','twitter','google_map','youtube_video'"
			),
			'content' => array(
				'type' => 'TEXT',
				'default' => ''
			)
		));
		$this->dbforge->add_key('component_id', TRUE);
		$this->dbforge->create_table('tab_content');
		
		
		$this->dbforge->add_field(array(
			'aliases_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'aliases' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('aliases_id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_aliases');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'child_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('child_id');
		$this->dbforge->create_table('topic_children');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'event_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_event');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'folder_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('folder_id');
		$this->dbforge->create_table('topic_folders');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'link_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('link_id');
		$this->dbforge->create_table('topic_link');
		
		
		$this->dbforge->add_field(array(
			'merge_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic1_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'topic2_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'unmerge' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			)
		));
		$this->dbforge->add_key('merge_id', TRUE);
		$this->dbforge->add_key('topic1_id');
		$this->dbforge->add_key('topic2_id');
		$this->dbforge->create_table('topic_merge');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->create_table('topic_newsfeed');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('page_id');
		$this->dbforge->create_table('topic_page');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'photo_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('photo_id');
		$this->dbforge->create_table('topic_photo');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'points' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_points');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'post_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_post');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'pr_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_pr');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'lft' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'rgt' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'level' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_relationship');
		
		
		$this->dbforge->add_field(array(
			'track_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'type' => array(
				'type' => 'ENUM',
				'constraint' => "'merge','unmerge'"
			),
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('track_id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('topic_tracker');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'a_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('topic_id');
		$this->dbforge->add_key('a_id');
		$this->dbforge->create_table('topic_user');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'topic_id' => array(
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
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_key('topic_id');
		$this->dbforge->create_table('topic_user_follow');
		
		
		$this->dbforge->add_field(array(
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
			'weight' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('user1_id');
		$this->dbforge->add_key('user2_id');
		$this->dbforge->create_table('usersimilarity');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
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
			'similarity' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('user1_id');
		$this->dbforge->add_key('user2_id');
		$this->dbforge->create_table('users_similarity');
		
		
		$this->dbforge->add_field(array(
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
			'similarity' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('user1_id');
		$this->dbforge->add_key('user2_id');
		$this->dbforge->create_table('user_percentile');
	}
}
