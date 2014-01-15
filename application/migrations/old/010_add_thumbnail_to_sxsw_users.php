<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_thumbnail_to_sxsw_users extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_users', array(
			'thumbnail'=>array(
				'type'=>'VARCHAR',
				'constraint'=>200,
				'default'=>0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_users', 'thumbnail');
	}
}
