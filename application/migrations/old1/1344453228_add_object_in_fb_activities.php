<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_object_in_fb_activities extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_activities', array(
			'object'=>array(
				'type' => 'VARCHAR',
				'constraint' => '30',
				'default' => ''
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('fb_activities', 'object');
	}
}
