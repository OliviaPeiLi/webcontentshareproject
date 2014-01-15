<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_link_url_to_sxsw_photos extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_photos', array(
			'link_url' => array(
				'type' => 'VARCHAR',
				'constraint' => '200',
			),
		));
		
		$this->dbforge->add_column('sxsw_photos', array(
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
		));
		
		$this->dbforge->add_column('sxsw_link_urls', array(
			'description' => array(
				'type' => 'VARCHAR',
				'constraint' => '500',
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_photos', 'link_url');
		$this->dbforge->drop_column('sxsw_photos', 'description');
		$this->dbforge->drop_column('sxsw_link_urls', 'description');
	}
}
