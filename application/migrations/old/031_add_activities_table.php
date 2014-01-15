<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_activities_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'user_id_to' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'page_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'page_id_to' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'folder_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
				'default' => null,
			),
			'activity_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('activities');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_table('activities');
	}
	
	
}
