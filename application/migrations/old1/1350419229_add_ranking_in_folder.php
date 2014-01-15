<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_ranking_in_folder extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('folder', array(
			'ranking'=>array(
				'type'=>'INT',
				'constraint'=>11,
				'default'=>0
			),
			'is_ranked'=>array(
				'type'=>'TINYINT',
				'constraint'=>3,
				'default'=>0
			),
			'ranked_at'=>array(
				'type' => 'TIMESTAMP',
				'default' => '0000-00-00 00:00:00'
			)
		));
	}

	public function down()
	{
		
		$this->dbforge->drop_column('folder', 'ranking');
		$this->dbforge->drop_column('folder', 'is_ranked');
		$this->dbforge->drop_column('folder', 'ranked_at');
		
	}
}
