<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user_id_in_fb_friends extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('fb_friends', array(
			'user_id'=>array(
				'type' => 'INT',
				'constraint' => '11',
				'default' => '0'
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('fb_friends', 'user_id');
	}
}
