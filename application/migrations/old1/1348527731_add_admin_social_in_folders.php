<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_admin_social_in_folders extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'admin_social'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('folder', 'admin_social');

	}
}
