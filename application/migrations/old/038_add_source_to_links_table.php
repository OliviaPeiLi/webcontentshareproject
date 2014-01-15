<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_source_to_links_table extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('links', array(
			'source' => array(
				'type' => 'text',
				'default' => '',
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('links', 'source');
	}
}
