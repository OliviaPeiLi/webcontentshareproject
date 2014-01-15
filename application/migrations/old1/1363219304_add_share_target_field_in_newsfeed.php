<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_share_target_field_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'share_target'=>array(
				'type' => 'INT',
				'constraint' => '10',
				'default' => 0,
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'share_target');
	}
}
