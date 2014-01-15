<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_edit_topics_table extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_column('topics', array(
			'editable'=>array(
				'type'=>'ENUM',
				'constraint'=>"'1','0'",
				'default'=>'1'
			),
		));
		
		$this->dbforge->drop_column('topics', 'avatar');
		$this->dbforge->drop_column('topics', 'thumbnail');
		$this->dbforge->drop_column('topics', 'user_hits');
		$this->dbforge->drop_column('topics', 'merge');
	}
	
	
	public function down()
	{
		$this->dbforge->drop_column('topics', 'editable');
		
		$this->dbforge->add_column('topics', array(
			'avatar'=>array(
				'type'=>'VARCHAR',
				'constraint'=>'100',
				'default'=>0
			),
		));
		$this->dbforge->add_column('topics', array(
			'thumbnail'=>array(
				'type'=>'VARCHAR',
				'constraint'=>'100',
				'default'=>0
			),
		));
		$this->dbforge->add_column('topics', array(
			'user_hits'=>array(
				'type'=>'INT',
				'constraint'=>'11',
				'default'=>0
			),
		));
		$this->dbforge->add_column('topics', array(
			'merge'=>array(
				'type'=>'ENUM',
				'constraint'=>"'0','1'",
			),
		));
	}
	
	
}
