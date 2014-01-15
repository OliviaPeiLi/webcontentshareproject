<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_time_to_sxsw extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('sxsw_links', array(
			'time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_column('sxsw_likes', array(
			'time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_column('sxsw_comments', array(
			'time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_column('sxsw_comment_likes', array(
			'time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_column('sxsw_links', array(
			'update_time'=>array(
				'type'=>'TIMESTAMP',

			)
		));
		$this->dbforge->add_column('sxsw_links', array(
			'comment_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		$this->dbforge->add_column('sxsw_links', array(
			'like_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		$this->dbforge->add_column('sxsw_comment_likes', array(
			'like_count'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			)
		));
		
	}

	public function down()
	{
		$this->dbforge->drop_column('sxsw_links', 'time');
		$this->dbforge->drop_column('sxsw_links', 'update_time');
		$this->dbforge->drop_column('sxsw_links', 'comment_count');
		$this->dbforge->drop_column('sxsw_links', 'like_count');
		$this->dbforge->drop_column('sxsw_comments', 'time');
		$this->dbforge->drop_column('sxsw_likes', 'time');
		$this->dbforge->drop_column('sxsw_comment_likes', 'time');
		$this->dbforge->drop_column('sxsw_comment_likes', 'like_count');
	}
}
