<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_time_to_sxsw_link_urls extends CI_Migration {

	public function up()
	{

		$this->dbforge->add_column('sxsw_link_urls', array(
			'time' => array(
				'type' => 'TIMESTAMP',
			),
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_link_urls', 'time');
	}
}
