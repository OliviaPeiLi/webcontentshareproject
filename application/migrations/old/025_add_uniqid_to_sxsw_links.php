<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_uniqid_to_sxsw_links extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('sxsw_links', array(
			'uniqid' => array(
				'type' => 'VARCHAR',
				'constraint'=>50,
				'default'=>0
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_links', 'uniqid');
	}
}
