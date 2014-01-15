<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_bookmarklet_settings_to_users extends CI_Migration {

	public function up()
	{		
		$this->dbforge->add_column('users', array(
			'bookmarklet_settings' => array(
				'type' => 'TINYINT',
				'default' => 1,
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('users', 'bookmarklet_settings');
	}
}
