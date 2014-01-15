<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_exclusive_in_folder extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'exclusive'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));

	}

	public function down()
	{
		
		$this->dbforge->drop_column('folder', 'exclusive');

	}
}
