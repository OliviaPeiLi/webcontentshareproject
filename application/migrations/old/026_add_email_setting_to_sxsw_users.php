<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_email_setting_to_sxsw_users extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('sxsw_users', array(
			'email_setting' => array(
				'type' => 'ENUM',
				'constraint' => "'1','0'"
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_users', 'email_setting');
	}
}
