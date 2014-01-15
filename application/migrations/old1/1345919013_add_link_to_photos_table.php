<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_link_to_photos_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('photos', array(
			'link'=>array(
				'type'=>'VARCHAR',
				'constraint'=>255,
				'default'=>''
			)
		));
		$this->dbforge->add_column('photos', array(
			'source'=>array(
				'type'=>'VARCHAR',
				'constraint'=>255,
				'default'=>''
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('photos', 'link');
		$this->dbforge->drop_column('photos', 'source');
	}
}
