<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_uniqview_to_newsfeed_folder extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'uniqview'=>array(
				'type' => 'BIGINT',
				'constraint' => '20',
				'default' => '0'
			),
		));
		
		$this->dbforge->add_column('folder', array(
			'uniqview'=>array(
				'type' => 'BIGINT',
				'constraint' => '20',
				'default' => '0'
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'uniqview');
		$this->dbforge->drop_column('folder', 'uniqview');
	}
	
	
}
