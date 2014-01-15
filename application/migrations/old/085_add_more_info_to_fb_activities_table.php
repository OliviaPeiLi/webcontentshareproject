<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_more_info_to_fb_activities_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_activities', array(
			'newsfeed_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
		
		$this->dbforge->add_column('fb_activities', array(
			'folder_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('fb_activities', 'newsfeed_id');
		$this->dbforge->drop_column('fb_activities', 'folder_id');
	}
	
	
}
