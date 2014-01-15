<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_time_in_alpha_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('alpha_users', array(
			'time'=>array(
				'type'=>'TIMESTAMP'
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('alpha_users', 'time');

	}
}
