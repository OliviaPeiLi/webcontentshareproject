<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_like_count_to_sxsw_comments_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_comments', array(
			'like_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_comments', 'like_count');
	}
}
