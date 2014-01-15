<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_hits_to_newsfeed_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'hits'=>array(
				'type' => 'BIGINT',
				'constraint' => '20',
				'default' => '0'
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'hits');
		
	}
	
	
}
