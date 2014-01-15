<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_twitter_id_on_fb_activities extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_activities', array(
			'twitter_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('fb_activities', 'twitter_id');
	}
	
	
}
