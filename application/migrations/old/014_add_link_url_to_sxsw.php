<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_link_url_to_sxsw extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_links', array(
			'link_url'=>array(
				'type'=>'VARCHAR',
				'constraint'=>200,
				'default'=>null
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_links', 'link_url');
	}
}
