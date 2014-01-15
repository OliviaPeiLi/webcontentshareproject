<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_edit_folders_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'hits'=>array(
				'type'=>'INT',
				'constraint'=>'11',
				'default'=>'0'
			),
		));

	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('folder', 'hits');
		
	}
	
	
}
