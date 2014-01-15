<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables extends CI_Migration {
	
	public function up()
	{
		$this->dbforge->drop_table('additional_info');
		$this->dbforge->drop_table('age_similarity');
		$this->dbforge->drop_table('albums');
		$this->dbforge->drop_table('cometchat');
		$this->dbforge->drop_table('cometchat_online');
		$this->dbforge->drop_table('cometchat_session');
		$this->dbforge->drop_table('cometchat_status');
		$this->dbforge->drop_table('cometchat_typing');
		$this->dbforge->drop_table('comments_children');
		$this->dbforge->drop_table('countries');
		$this->dbforge->drop_table('country');
		$this->dbforge->drop_table('custom_tabs');
	}

	public function down()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'type_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'default' => ''
			),
			'general_type' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => ''
			),
			'hits' => array(
				'type' => 'INT',
				'constraint' => 20,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('additional_info');
		
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
		$this->dbforge->create_table('similarity');
		
		$this->dbforge->add_field(array(
			'album_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'loop_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'topic_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'album_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 128,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('album_id', TRUE);
		$this->dbforge->create_table('albums');
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'from' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'to' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'sent' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0
			),
			'read' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			'direction' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			'message' => array(
				'type' => 'TEXT',
				'default' => '',
			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('cometchat');
		
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('userid');
		$this->dbforge->create_table('cometchat_online');
		
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'selfonline' => array(
				'type' => 'INT',
				'constraint' => 1,
				'default' => ''
			),
			'soundmute' => array(
				'type' => 'INT',
				'constraint' => 1,
				'default' => ''
			),
			'friendlistid' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => ''
			)
		));
		$this->dbforge->add_key('userid', TRUE);
		$this->dbforge->create_table('cometchat_session');
		
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'typingto' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'typingtime' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'message' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'status' => array(
				'type' => 'VARCHAR',
				'constraint' => 10,
				'default' => ''
			)
		));
		$this->dbforge->add_key('userid', TRUE);
		$this->dbforge->create_table('cometchat_status');
		
		$this->dbforge->add_field(array(
			'userid' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'friend' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'time' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			)
		));
		$this->dbforge->add_key('userid');
		$this->dbforge->add_key('friend');
		$this->dbforge->create_table('cometchat_typing');
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'comment_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'default' => ''
			),
			'children_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('comment_id');
		$this->dbforge->add_key('children_id');
		$this->dbforge->create_table('comments_children');
		
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 5,
				'default' => ''
			),
			'iso2' => array(
				'type' => 'CHAR',
				'constraint' => 2,
				'default' => ''
			),
			'iso3' => array(
				'type' => 'CHAR',
				'constraint' => 3,
				'default' => ''
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('iso2');
		$this->dbforge->add_key('iso3');
		$this->dbforge->add_key('name');
		$this->dbforge->create_table('countries');
		
		$this->dbforge->add_field(array(
			'country_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'country' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'fips104' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'iso2' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'iso3' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'ison' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'internet' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'capital' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'mapreference' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'nationalitysingular' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'nationalityplural' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'currency' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'currencycode' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'population' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
			'comment' => array(
				'type' => 'VARCHAR',
				'constraint' => 250,
				'default' => ''
			),
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('country');
		
		$this->dbforge->add_field(array(
			'tab_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => ''
			),
			'page_id' => array(
				'type' => 'CHAR',
				'constraint' => 11,
				'default' => ''
			),
			'tab_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 20,
				'default' => ''
			),
			'activated' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'default' => 0
			),
			'time' => array(
				'type' => 'TIMESTAMP'
			)
		));
		$this->dbforge->add_key('tab_id', true);
		$this->dbforge->create_table('custom_tabs');
	}

}
