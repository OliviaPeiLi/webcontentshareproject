<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_category_to_sxsw_photos extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_photos', array(
			'category' => array(
				'type' => 'ENUM',
				'constraint' => "'1','2','3'",
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_photos', 'category');
	}
}
