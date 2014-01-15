<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_coversheet_updated_in_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'coversheet_updated'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('newsfeed', 'coversheet_updated');

	}
}
