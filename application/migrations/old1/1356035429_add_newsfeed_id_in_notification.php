<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_newsfeed_id_in_notification extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('notifications', array(
			'newsfeed_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('notifications', 'newsfeed_id');
	}
}
