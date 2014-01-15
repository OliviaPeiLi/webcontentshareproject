<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_04 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('prs');
		$this->dbforge->drop_table('psk_page_similarity');
		$this->dbforge->drop_table('psk_topic_similarity');
		$this->dbforge->drop_table('region');
		$this->dbforge->drop_table('report');
		$this->dbforge->drop_table('Sheet1');
		$this->dbforge->drop_table('should_know_similarity');
		$this->dbforge->drop_table('srt_sort_category');
		$this->dbforge->drop_table('srt_sort_interest');
		$this->dbforge->drop_table('sxsw_comments');
		$this->dbforge->drop_table('sxsw_comment_likes');
		$this->dbforge->drop_table('sxsw_emails');
		$this->dbforge->drop_table('sxsw_likes');
		$this->dbforge->drop_table('sxsw_links');
		$this->dbforge->drop_table('sxsw_link_urls');
		$this->dbforge->drop_table('sxsw_photos');
		$this->dbforge->drop_table('sxsw_users');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'pr_id' => array(
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
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'source_title' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			),
			'source_link' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('pr_id', TRUE);
		$this->dbforge->create_table('prs');
		
		
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
		$this->dbforge->create_table('psk_page_similarity');
		
		
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
		$this->dbforge->create_table('psk_page_similarity');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'region_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'country_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'region' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'code' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'adm1_code' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('region_id');
		$this->dbforge->add_key('country_id');
		$this->dbforge->create_table('region');
		
		
		$this->dbforge->add_field(array(
			'r_id' => array(
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
			'post_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'photo_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'link_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'comment_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'hits' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('r_id', TRUE);
		$this->dbforge->create_table('report');
		
		
		$this->dbforge->add_field(array(
			'password' => array(
				'type' => 'VARCHAR',
				'constraint' => 12,
				'default' => ''
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 17,
				'default' => ''
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 27,
				'default' => ''
			),
			'username' => array(
				'type' => 'VARCHAR',
				'constraint' => 15,
				'default' => ''
			)
		));
		$this->dbforge->create_table('Sheet1');
		
		
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
		$this->dbforge->create_table('should_know_similarity');
		
		
		$this->dbforge->add_field(array(
			'id_user' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'id_category' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'ordering' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id_user');
		$this->dbforge->create_table('srt_sort_category');
		
		
		$this->dbforge->add_field(array(
			'id_user' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'id_interest' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'ordering' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id_user');
		$this->dbforge->create_table('srt_sort_interest');
		
		
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
			'link_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'comment' => array(
				'type' => 'VARCHAR',
				'constraint' => 300,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			),
			'like_count' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_comments');
		
		
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
			'comment_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			),
			'like_count' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_comment_likes');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => '',
				'auto_increment' => TRUE
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'code' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'like_count' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_emails');
		
		
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
			'link_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_likes');
		
		
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
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => 1000,
				'default' => ''
			),
			'image' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			),
			'update_time' => array(
				'type' => 'TIMESTAMP'
			),
			'comment_count' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'like_count' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'link_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'default' => ''
			),
			'news_rank' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => ''
			),
			'category' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			),
			'uniqid' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			),
			'media' => array(
				'type' => 'TEXT',
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_links');
		
		
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
			'link_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'category' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_link_urls');
		
		
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
			'image' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'link_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'category' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			),
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_photos');
		
		
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
			'twitter_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'first_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'last_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'email' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'default' => ''
			),
			'thumbnail' => array(
				'type' => 'VARCHAR',
				'constraint' => 200,
				'default' => ''
			),
			'post_status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			'email_setting' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 1
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('sxsw_users');
	}
}
