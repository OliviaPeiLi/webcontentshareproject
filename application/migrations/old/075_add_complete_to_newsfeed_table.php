<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_complete_to_newsfeed_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'complete'=>array(
				'type' => 'ENUM',
				'constraint' => "'1','0'",
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'complete');
		
	}
	
	
}
