<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_01 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('events');
		$this->dbforge->drop_table('favorite_anwser');
		$this->dbforge->drop_table('favorite_question');
		$this->dbforge->drop_table('food_type');
		$this->dbforge->drop_table('interest_category');
		$this->dbforge->drop_table('invitees');
		$this->dbforge->drop_table('lists');
		$this->dbforge->drop_table('list_order');
		$this->dbforge->drop_table('list_page');
		$this->dbforge->drop_table('list_users');
		$this->dbforge->drop_table('location_similarity');
		$this->dbforge->drop_table('loops');
		$this->dbforge->drop_table('loop_order');
		$this->dbforge->drop_table('loop_user');
		$this->dbforge->drop_table('newsfeed_loops');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'event_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'start_time' => array(
				'type' => 'TIMESTAMP'
			),
			'end_time' => array(
				'type' => 'TIMESTAMP'
			),
			'event_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'location' => array(
				'type' => 'VARCHAR',
				'constraint' => 55,
				'default' => ''
			),
			'address' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'city' => array(
				'type' => 'VARCHAR',
				'constraint' => 55,
				'default' => ''
			),
			'zip_code' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'description' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'privacy' => array(
				'type' => 'ENUM',
				'constraint' => "'public','private'"
			),
			'attendees' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'notification' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			),
			'img' => array(
				'type' => 'VARCHAR',
				'constraint' => 128,
				'default' => ''
			),
		));
		$this->dbforge->add_key('event_id', TRUE);
		$this->dbforge->create_table('events');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
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
			'q_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'question' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			),
			'display' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => ''
			)
		));
		$this->dbforge->add_key('q_id', TRUE);
		$this->dbforge->create_table('favorite_question');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('food_type');
		
		
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
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('interest_category');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'event_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'response' => array(
				'type' => 'ENUM',
				'constraint' => "'yes','no'"
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('event_id');
		$this->dbforge->add_key('user_id');
		$this->dbforge->create_table('invitees');
		
		
		$this->dbforge->add_field(array(
			'list_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'list_maker_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'list_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			),
			'description' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'visibility' => array(
				'type' => 'ENUM',
				'constraint' => "'yes','no'"
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('list_id', TRUE);
		$this->dbforge->create_table('lists');
		
		
		$this->dbforge->add_field(array(
			'uid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'order' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('uid', TRUE);
		$this->dbforge->create_table('list_order');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'list_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('list_id');
		$this->dbforge->add_key('page_id');
		$this->dbforge->create_table('list_page');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'list_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'list_user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('list_id');
		$this->dbforge->add_key('list_user_id');
		$this->dbforge->create_table('list_users');
		
		
		$this->dbforge->add_field(array(
			'user1_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
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
		$this->dbforge->create_table('location_similarity');
		
		
		$this->dbforge->add_field(array(
			'loop_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'loop_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('loop_id', true);
		$this->dbforge->create_table('loops');
		
		
		$this->dbforge->add_field(array(
			'uid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'order' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('uid', true);
		$this->dbforge->create_table('loop_order');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'loop_id' => array(
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
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('loop_user');
		
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'newsfeed_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'loop_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('newsfeed_id');
		$this->dbforge->add_key('loop_id');
		$this->dbforge->create_table('newsfeed_loops');
	}
}
