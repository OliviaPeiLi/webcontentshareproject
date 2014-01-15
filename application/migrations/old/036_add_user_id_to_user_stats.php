<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_user_id_to_user_stats extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('user_stats', array(
			'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'user_id');
	}
}
