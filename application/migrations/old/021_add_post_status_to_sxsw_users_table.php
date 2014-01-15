<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_post_status_to_sxsw_users_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_users', array(
			'post_status' => array(
				'type' => 'ENUM',
				'constraint' => "'0','1'",
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_users', 'post_status');
	}
}
