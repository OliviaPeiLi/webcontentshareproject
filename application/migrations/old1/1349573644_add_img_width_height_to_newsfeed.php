<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_img_width_height_to_newsfeed extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('newsfeed', array(
			'img_width'=>array(
				'type'=>'INT',
				'constraint'=>1,
				'default'=>0
			),
			'img_height'=>array(
				'type'=>'INT',
				'constraint'=>1,
				'default'=>0
			),
		));
		
	}

	public function down()
	{
		  $this->dbforge->drop_column('newsfeed', 'img_width');
		  $this->dbforge->drop_column('newsfeed', 'img_height');
	}
}
