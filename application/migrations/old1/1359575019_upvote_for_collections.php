<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Upvote_for_collections extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('likes', array(
			'folder_id'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
		$this->dbforge->add_column('folder', array(
			'up_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('likes', 'folder_id');
		$this->dbforge->drop_column('folder', 'up_count');

	}
}
