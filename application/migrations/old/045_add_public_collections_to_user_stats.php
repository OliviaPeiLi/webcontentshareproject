<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_public_collections_to_user_stats extends CI_Migration {

	public function up()
	{		
		$this->dbforge->add_column('user_stats', array(
			'public_collections' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'public_collections');
	}
}
