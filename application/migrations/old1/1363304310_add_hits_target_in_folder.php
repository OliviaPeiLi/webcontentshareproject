<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_hits_target_in_folder extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'hits_target'=>array(
				'type' => 'INT',
				'constraint' => '10',
				'default' => 0,
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('folder', 'hits_target');
	}
}
