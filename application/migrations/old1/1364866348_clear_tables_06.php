<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Clear_tables_06 extends CI_Migration {

	public function up()
	{
		$this->dbforge->drop_table('work_similarity');
		$this->dbforge->drop_table('wiki');
	}

	public function down()
	{
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
		$this->dbforge->create_table('work_similarity');
		
		
		$this->dbforge->add_field(array(
			'page_url' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'page_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'category_name' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			),
			'image_url' => array(
				'type' => 'BLOB',
				'default' => ''
			),
			'intro' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'abstact' => array(
				'type' => 'TEXT',
				'default' => ''
			),
			'main_topic' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,
				'default' => ''
			)
		));
		$this->dbforge->create_table('wiki');
	}
}
