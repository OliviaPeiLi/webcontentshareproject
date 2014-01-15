<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_hashtag_folders extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'hashtag_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			),
			'owner_visited'=>array(
				'type'=>'TINYINT',
				'constraint'=>1,
				'default'=>0
			)
		));
	}

	public function down()
	{
		$this->dbforge->drop_column('folder', 'hashtag_id');
		$this->dbforge->drop_column('folder', 'owner_visited');
	}
}
