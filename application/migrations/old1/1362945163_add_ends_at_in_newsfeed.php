<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_ends_at_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'ends_at'=>array(
				'type' => 'TIMESTAMP'
			),
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('newsfeed', 'ends_at');
	}
}
