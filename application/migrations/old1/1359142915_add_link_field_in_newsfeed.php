<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_link_field_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'link_url'=>array(
				'type'=>'VARCHAR',
				'constraint'=>2048,
				'default'=>''
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'link');
	}
}
