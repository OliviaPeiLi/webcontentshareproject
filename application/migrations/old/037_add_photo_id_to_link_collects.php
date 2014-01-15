<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_photo_id_to_link_collects extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('link_collects', array(
			'photo_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('link_collects', 'photo_id');
	}
}
