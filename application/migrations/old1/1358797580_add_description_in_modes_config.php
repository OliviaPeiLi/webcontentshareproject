<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_description_in_modes_config extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('modes_config', array(
			'description'=>array(
				'type'=>'text',
				'default'=>''
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('modes_config', 'description');
	}
}
