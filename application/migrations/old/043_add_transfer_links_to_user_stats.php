<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_transfer_links_to_user_stats extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('user_stats', array(
			'transfer_links' => array(
				'type' => 'INT',
				'constraint' => 11,
				'default' => 0,
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('user_stats', 'transfer_links');
	}
}
