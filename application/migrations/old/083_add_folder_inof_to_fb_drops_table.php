<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_folder_inof_to_fb_drops_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_drops', array(
			'folder_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		$this->dbforge->add_column('fb_drops', array(
			'time'=>array(
				'type' => 'TIMESTAMP',
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('folder_id', 'folder_id');
		$this->dbforge->drop_column('time', 'folder_id');
	}
	
	
}
