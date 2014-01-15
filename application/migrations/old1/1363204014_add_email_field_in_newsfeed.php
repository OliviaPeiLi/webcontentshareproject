<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_email_field_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'sxsw_email'=>array(
				'type' => 'VARCHAR',
				'constraint' => '100',
				'default' => '',
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'sxsw_email');
	}
}
