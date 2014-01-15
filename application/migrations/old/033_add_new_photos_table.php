<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_new_photos_table extends CI_Migration {


	public function up()
	{
		$this->dbforge->add_field(array(
			'photo_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => TRUE
			),
			'user_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'page_id_from' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'user_id_to' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'page_id_to' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'full_img' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			),
			'thumbnail' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			),
			'caption' => array(
				'type' => 'VARCHAR',
				'constraint' => '300',
				'default' => 0,
			),
            'folder_id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => 0,
			),
			'time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_key('photo_id', TRUE);
		$this->dbforge->create_table('photos');
	}
	
	
	public function down()
	{
        $this->dbforge->drop_table('photos');
	}
	
}
