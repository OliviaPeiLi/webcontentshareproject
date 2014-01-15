<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_more_info_to_fb_drops_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_drops', array(
			'first_name'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => ''
			),
		));
		
		$this->dbforge->add_column('fb_drops', array(
			'last_name'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => ''
			),
		));
		
		$this->dbforge->add_column('fb_drops', array(
			'username'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => ''
			),
		));
		
		$this->dbforge->add_column('fb_drops', array(
			'email'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => ''
			),
		));
		
		$this->dbforge->add_column('fb_drops', array(
			'gender'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => ''
			),
		));
		
		$this->dbforge->add_column('fb_drops', array(
			'birthday'=>array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('fb_drops', 'first_name');
		$this->dbforge->drop_column('fb_drops', 'last_name');
		$this->dbforge->drop_column('fb_drops', 'username');
		$this->dbforge->drop_column('fb_drops', 'email');
		$this->dbforge->drop_column('fb_drops', 'gender');
		$this->dbforge->drop_column('fb_drops', 'birthday');
	}
	
	
}
